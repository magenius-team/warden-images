<?php
if (extension_loaded('xhprof')) {
    $xhprofData = xhprof_disable();
    $data = json_encode([
        'profile' => $xhprofData,
        'tags' => [],
        'app_name' => getenv('WARDEN_ENV_NAME'),
        'hostname' => gethostname(),
        'date' => (new DateTimeImmutable())->getTimestamp()
    ]);

    try {
        $ch = curl_init('http://buggregator:8000/api/profiler/store');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_exec($ch);
    } catch (Exception $e) {
        // Do nothing
    }
    curl_close($ch);
}
