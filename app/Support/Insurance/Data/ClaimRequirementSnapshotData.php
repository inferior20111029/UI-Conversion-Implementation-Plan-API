<?php

namespace App\Support\Insurance\Data;

use JsonSerializable;

class ClaimRequirementSnapshotData implements InsuranceDataObject, JsonSerializable
{
    public function __construct(
        public ?bool $diagnosisWithChipId = null,
        public ?bool $originalReceiptRequired = null,
        public ?bool $digitalRecordsSupported = null,
        public ?bool $referralRuleRequired = null,
    ) {
    }

    public static function fromArray(array $payload): static
    {
        return new static(
            diagnosisWithChipId: array_key_exists('diagnosis_with_chip_id', $payload) ? (bool) $payload['diagnosis_with_chip_id'] : null,
            originalReceiptRequired: array_key_exists('original_receipt_required', $payload) ? (bool) $payload['original_receipt_required'] : null,
            digitalRecordsSupported: array_key_exists('digital_records_supported', $payload) ? (bool) $payload['digital_records_supported'] : null,
            referralRuleRequired: array_key_exists('referral_rule_required', $payload) ? (bool) $payload['referral_rule_required'] : null,
        );
    }

    public function toArray(): array
    {
        return [
            'diagnosis_with_chip_id' => $this->diagnosisWithChipId,
            'original_receipt_required' => $this->originalReceiptRequired,
            'digital_records_supported' => $this->digitalRecordsSupported,
            'referral_rule_required' => $this->referralRuleRequired,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
