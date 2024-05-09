<?php

use Illuminate\Support\Facades\Broadcast;

// basically just serves as security for the channels sort of optional if you want to make it public
//Broadcast::channel('user.{userId}', function (User $user) {
//    return true;
//});
//
//Broadcast::channel('user_reset_channel', function () {
//    return true;
//});
//
//Broadcast::channel('start_user_clicks', function () {
//    return true;
//});
