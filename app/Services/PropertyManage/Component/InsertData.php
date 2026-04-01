<?php

declare(strict_types=1);

namespace App\Services\PropertyManage\Component;

use Illuminate\Http\Request;

use App\Models\PropertyDocument;
use App\Support\Tool\File\FileMagic;

use App\Models\Fees;
use App\Models\Decoration;
use App\Models\Property;
use App\Models\PropertyContactInfo;
use App\Models\PropertyContactPerson;
use App\Models\AttachedCarpark;
use App\Models\NeighborhoodTransportation;
use App\Models\NeighborhoodLivability;
use App\Models\AttachedEquipment;
use App\Models\RentItemsIncluded;
use App\Models\ItemCheckIn;

final class InsertData
{
    use \App\Support\Trait\PropertyManage\ColumnTrait;

    /**
     * @param Request $request Request
     */
    public function __construct(
        private readonly Request $request
    ) {
    }

    /**
     * 取得物件建立資料
     *
     * @return Property
     */
    public function contract(): Property
    {
        return new Property($this->fetchContractColumnData($this->request)->noHaveMacro()->toColumnArray());
    }

    /**
     * 取得包含項目建立資料
     *
     * @return array
     */
    public function itemsIncluded(): array
    {
        $itemsIncluded = (array) $this->request->post('items_included');

        if (empty($itemsIncluded)) {
            return [];
        }

        return array_map(function (int $value): RentItemsIncluded {
            return new RentItemsIncluded(
                $this->fetchItemsIncludedColumnData($value)->noHaveMacro()->toColumnArray()
            );
        }, $itemsIncluded);
    }

    /**
     * 取得裝潢程度建立資料
     *
     * @return Decoration
     */
    public function decoration(): Decoration
    {
        $decoration = $this->request->post('decoration');

        if (!empty($decoration)) {
            return new Decoration($this->fetchDecorationColumnData($decoration)->noHaveMacro()->toColumnArray());
        }

        return new Decoration();
    }

    /**
     * 取得費用建立資料
     *
     * @return Fees
     */
    public function fees(): Fees
    {
        $fees = $this->request->post('fees');

        if (!empty($fees)) {
            return new Fees($this->fetchFeesColumnData($fees + ['price' => $this->request->post('fees')])
                ->noHaveMacro()
                ->toColumnArray());
        }

        return new Fees();
    }

    /**
     * 取得附設車位建立資料
     *
     * @return array
     */
    public function carpark(): array
    {
        $carpark = (array) $this->request->post('carpark');

        return array_map(function (array $value): AttachedCarpark {
            return new AttachedCarpark($this->fetchCarparkColumnData($value)->noHaveMacro()->toColumnArray());
        }, $carpark);
    }

    /**
     * 取得附設設備建立資料
     *
     * @return array
     */
    public function equipment(): array
    {
        $equipment = (array) $this->request->post('equipment');

        return array_map(function (array $crmEquipment): AttachedEquipment {
            return new AttachedEquipment(
                $this->fetchEquipmentColumnData($crmEquipment)->toColumnArray()
            );
        }, $equipment);
    }

    /**
     * 取得附近交通建立資料
     *
     * @return array
     */
    public function neighborhoodTransportation(): array
    {
        $values = (array) $this->request->post('transportation');

        return array_map(
            fn (array $value): NeighborhoodTransportation => new NeighborhoodTransportation(
                $this->fetchTransportationColumnData($value)->noHaveMacro()->toColumnArray()
            ),
            array_filter($values, fn ($value) => isset($value['type']))
        );
    }

    /**
     * 取得附近生活機能建立資料
     *
     * @return array
     */
    public function neighborhoodLivability()
    {
        $values = (array) $this->request->post('livability');

        return array_map(function (int $values): NeighborhoodLivability {
            return new NeighborhoodLivability(
                $this->fetchLivabilityColumnData($values)->noHaveMacro()->toColumnArray()
            );
        }, $values);
    }

    /**
     * 最短租期 & 可遷入日
     *
     * @return ItemCheckIn
     */
    public function checkInInfo(): ItemCheckIn
    {
        $values = (array)$this->request->post('checkInInfo');

        if (!empty($values)) {
            return new ItemCheckIn(
                $this->fetchCheckInColumnData($values)->noHaveMacro()->toColumnArray()
            );
        }

        return new ItemCheckIn();
    }

    /**
     * 聯絡人資料-聯絡方式
     *
     * @return array
     */
    public function contactInfo(): array
    {
        $values = (array) $this->request->post('contactInfo');

        if (empty($values)) {
            return [];
        }

        $contact = collect($values['type'])
            ->map(fn ($type, $key) => [
                'info' => $values['info'][$key] ?? null,
                'type' => $type,
            ])
            ->filter(fn ($item) => !is_null($item['type']) && $item['info'] !== '')
            ->values()
            ->toArray();

        return array_map(function (array $contact): PropertyContactInfo {
            return new PropertyContactInfo(
                $this->fetchContactInfoColumnData($contact)->noHaveMacro()->toColumnArray()
            );
        }, $contact);
    }

    /**
     * 聯絡人資料-聯絡人
     *
     * @return array
     */
    public function contactPerson(): PropertyContactPerson
    {
        $values = (array) $this->request->post('contactPerson');

        if ($values['type'] !== null && $values['type'] !== '') {
            return new PropertyContactPerson(
                $this->fetchContactPersonColumnData($values)->noHaveMacro()->toColumnArray()
            );
        }

        return new PropertyContactPerson();
    }

    /**
     * 物件文件資訊
     *
     * @return array
     */
    public function document(): array
    {
        $documentRequest = $this->request->post('document');

        $pictureIds = array_map(fn($item) => $item['uuid'], $documentRequest['picture'] ?? []);

        $videoId = array_map(fn($item) => $item['uuid'], $documentRequest['video'] ?? []);
        $URLData = data_get($documentRequest, 'URL');

        $pictureFiles = FileMagic::find($pictureIds)->get();
        $videoFile    = FileMagic::find($videoId)->get();
        $relationKey  = $this->fetchPropertyRelationKey(PropertyDocument::class);

        $pictures = !is_null($pictureFiles) ? $this->createPropertyDocuments($pictureFiles, 'picture', $relationKey) : [];
        $video    = !is_null($videoFile) ? $this->createPropertyDocuments($videoFile, 'video', $relationKey) : [];
        $URL      = $URLData ? $this->createPropertyDocument($URLData, 'URL', $relationKey) : null;

        return array_filter([...$pictures, ...$video, $URL]);
    }

    /**
     * @param  array|null  $files
     * @param  string  $type
     * @param  string  $relationKey
     *
     * @return array
     */
    private function createPropertyDocuments(?array $files, string $type, string $relationKey): array
    {
        if (!is_null($files)) {
            return array_map(function ($file) use ($type, $relationKey) {
                return $this->createPropertyDocument($file, $type,$relationKey);
            }, $files);
        }
    }

    /**
     * @param $file
     * @param  string  $type
     * @param  string  $relationKey
     *
     * @return PropertyDocument|null
     */
    private function createPropertyDocument($file, string $type, string $relationKey): ?PropertyDocument
    {
        if ($type == 'video' || $type == 'picture') {
            return $file ? new PropertyDocument(
                $this->fetchDocumentColumnData((int) data_get($file, 'id'), $type)
                    ->excludeColumn($relationKey)
                    ->toColumnArray()
            ) : null;
        } else {
            return $file ? new PropertyDocument(
                $this->fetchDocumentColumnData(0, $type, $file)
                    ->excludeColumn($relationKey)
                    ->toColumnArray()
            ) : null;
        }
    }
}
