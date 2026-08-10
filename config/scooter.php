<?php

return [

    'reservation_timeout_minutes' => (int) env(
        'SCOOTER_RESERVATION_TIMEOUT',
        10
    ),

];