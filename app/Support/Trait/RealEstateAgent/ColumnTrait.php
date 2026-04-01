<?php

declare(strict_types=1);

namespace App\Support\Trait\RealEstateAgent;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use App\Support\Tool\File\FileMagic;
use App\Support\Data\RealEstateAgentData;
use App\Support\Data\RealEstateAgentTokenData;

trait ColumnTrait
{
    /**
     * 取得欄位資料
     *
     * @param \Illuminate\Http\Request $request Request
     *
     * @return \App\Support\Data\RealEstateAgentData
     */
    public function fetchColumnData(Request $request): RealEstateAgentData
    {
        return new RealEstateAgentData([
            'uuid' => str()->uuid()->toString(),
            'identificationCode' => (string) str()->ulid(),
            'avatar' => (int) FileMagic::find((string) $request->post('avatar'))->get()?->id
        ] + $request->all());
    }

    /**
     * 取得 token 欄位資料
     *
     * @param string|null $type
     *
     * @return \App\Support\Data\RealEstateAgentTokenData
     */
    public function fetchTokenColumnData(?string $type = null): RealEstateAgentTokenData
    {
        return new RealEstateAgentTokenData(compact('type') + [
            'token' => str()->random(40),
            'expiresAt' => now()->addMinutes(10)
        ]);
    }

    /**
     * 取得密碼欄位資料
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function fetchPasswordColumn(Request $request): array
    {
        if (!empty($request->post('password'))) {
            $password = Hash::make((string) $request->post('password'));
            return compact('password');
        }

        return [];
    }
}
