<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/



Broadcast::channel('private-device-add', function () {
    return true;
});

Broadcast::channel('private-message-received', function () {
    return true;
});

Broadcast::channel('private-message-send', function () {
    return true;
});

Broadcast::channel('device-logout', function () {
    return true;
});
