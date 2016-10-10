<?php

namespace AccessibilityBarriersBundle\Notification\Strategies;

use AccessibilityBarriersBundle\Entity\Notification;
use OAuthBundle\Entity\User;

class SmsStrategy implements SendingStrategy
{
    const TOKEN = "wygenerowany_token";


    public function send(User $user, Notification $notification)
    {
        $params = array(
            'to' => '500000000',
            'from' => 'Info',
            'message' => "Hello world!",
        );

        $this->smsSend($params, self::TOKEN);
    }

    private function smsSend($params, $token, $backup = false)
    {

        if ($backup == true) {
            $url = 'https://api2.smsapi.pl/sms.do';
        } else {
            $url = 'https://api.smsapi.pl/sms.do';
        }

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $params);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($c, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $token"
        ));

        $content = curl_exec($c);
        $http_status = curl_getinfo($c, CURLINFO_HTTP_CODE);

        if ($http_status != 200 && $backup == false) {
            $backup = true;
            $this->smsSend($params, $token, $backup);
        }

        curl_close($c);

        return $content;
    }

    private function replaceSpecialChars($string)
    {
        return strtr($string, 'ĘÓĄŚŁŻŹĆŃęóąśłżźćń', 'EOASLZZCNeoaslzzcn');
    }

    private function maxString($string, $max)
    {
        return substr($string, 0, $max);
    }
}
