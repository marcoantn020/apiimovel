#### personalizar host
    - sudo nano /etc/hosts
        127.0.0.1       project.test
        ::1     ip6-project.test ip6-loopback

    - php artisan serve --host=project.test --port=80

#### var_dump
    - dd(); Dump / Die
    - ddd(); Dump / Die / Debug
    - dump(); Dump

#### multi routes
    Route::match(['get', 'post'],'/user/test', static function () {
        echo 'funcionando';
    });