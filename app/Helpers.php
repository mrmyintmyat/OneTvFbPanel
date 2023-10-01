<?php

function formatDateWithTimezone($timestamp, $format, $timezoneOffset)
{
    // try {
        // Convert the original time (in seconds) to a DateTime object
        $dateTime = DateTime::createFromFormat('U', $timestamp);

        // Apply the user's timezone offset
        $dateTime->modify("$timezoneOffset hours");

        // Format the DateTime object as a string
        $formattedTime = $dateTime->format($format);

        return $formattedTime;
    // } catch (Exception $e) {
    //     return 'Invalid Date'; // Provide a default or error message
    // }
}
