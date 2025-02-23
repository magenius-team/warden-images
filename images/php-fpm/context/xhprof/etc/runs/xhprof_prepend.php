<?php
if (extension_loaded('xhprof')) {
    xhprof_enable(
        XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY,
        [
            'ignored_functions' => ['xhprof_disable']
        ]
    );

    register_shutdown_function(function () {
        $xhprofData = xhprof_disable();
        $tags = [];
        if (PHP_SAPI === 'cli') {
            global $argv;
            $tags['command'] = implode(' ', $argv);
        } else {
            $tags['url'] = $_SERVER['REQUEST_URI'];
        }

        $data = json_encode([
            'profile' => $xhprofData,
            'tags' => $tags,
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
    });
}
