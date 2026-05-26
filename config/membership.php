<?php
const MEMBERSHIP_TIERS = [
    'reguler' => [
        'label'       => 'Reguler',
        'badge_class' => 'badge-reguler',
        'disc_default' => 0,
        'disc_bulk'   => 0,
        'bulk_min_kg' => 6,
        'desc'        => 'Tidak ada diskon',
    ],
    'member' => [
        'label'       => 'Member',
        'badge_class' => 'badge-member',
        'disc_default' => 5,
        'disc_bulk'   => 10,
        'bulk_min_kg' => 6,
        'desc'        => 'Diskon 5% untuk semua order, 10% untuk order ≥ 6 kg',
    ],
];

function getTier(string $m): array {
    return MEMBERSHIP_TIERS[$m] ?? MEMBERSHIP_TIERS['reguler'];
}

function getDiscountPersen(string $m, int $berat): int {
    $t = getTier($m);
    if ($t['disc_default'] === 0) return 0;
    return ($berat >= $t['bulk_min_kg'] && $t['disc_bulk'] > 0) ? $t['disc_bulk'] : $t['disc_default'];
}

function hitungHargaAkhir(float $dasar, string $m, int $berat): array {
    $p = getDiscountPersen($m, $berat);
    $nominal = round($dasar * $p / 100);
    return [
        'harga_dasar'    => (int) $dasar,
        'diskon_persen'  => $p,
        'diskon_nominal' => (int) $nominal,
        'total_harga'    => (int) ($dasar - $nominal),
    ];
}
