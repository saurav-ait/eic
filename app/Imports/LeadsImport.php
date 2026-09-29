<?php

namespace App\Imports;

use App\Models\ActivityType;
use App\Models\Country;
use App\Models\Lead;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LeadsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Lead([
            'company_name' => $this->firstValue($row, ['company_name', 'company', 'Company Name'], $row[0] ?? null),
            'director' => $this->firstValue($row, ['director', 'Director'], $row[1] ?? null),
            'phone' => $this->firstValue($row, ['phone', 'Phone'], $row[2] ?? null),
            'email' => $this->firstValue($row, ['email', 'Email'], $row[3] ?? null),
            'city' => $this->firstValue($row, ['city', 'City'], $row[4] ?? null),
            'address' => $this->firstValue($row, ['address', 'Address'], $row[5] ?? null),
            'country_id' => $this->resolveForeignKey($row, ['country_id', 'country', 'Country'], Country::class, 6),
            'activity_type_id' => $this->resolveForeignKey($row, ['activity_type_id', 'activity_type', 'Activity Type'], ActivityType::class, 7),
            'status' => $this->normalizeStatus($this->firstValue($row, ['status', 'Status'], $row[8] ?? 'New')),
        ]);
    }

    protected function firstValue(array $row, array $keys, $default = null)
    {
        foreach ($keys as $key) {
            if (array_key_exists($key, $row) && $row[$key] !== null && $row[$key] !== '') {
                return $row[$key];
            }

            $normalized = strtolower(str_replace(' ', '_', $key));
            if (array_key_exists($normalized, $row) && $row[$normalized] !== null && $row[$normalized] !== '') {
                return $row[$normalized];
            }
        }

        return $default;
    }

    protected function resolveForeignKey(array $row, array $keys, string $modelClass, int $fallback)
    {
        $value = $this->firstValue($row, $keys, $row[$fallback] ?? null);

        if ($value === null || $value === '') {
            throw new \InvalidArgumentException(sprintf('%s is required.', $keys[0]));
        }

        if (is_numeric($value)) {
            $id = (int) $value;

            if (!$modelClass::query()->whereKey($id)->exists()) {
                throw new \InvalidArgumentException(sprintf('%s "%s" was not found.', $keys[0], $value));
            }

            return $id;
        }

        $lookup = trim((string) $value);
        $id = $modelClass::query()->where('name', $lookup)->value('id');

        if ($id === null) {
            throw new \InvalidArgumentException(sprintf('%s "%s" was not found.', $keys[0], $lookup));
        }

        return $id;
    }

    protected function normalizeStatus($value)
    {
        $status = trim((string) ($value ?? 'New'));

        return $status !== '' ? $status : 'New';
    }
}