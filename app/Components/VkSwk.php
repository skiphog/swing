<?php

namespace App\Components;

class VkSwk
{

    /**
     * Версия API
     *
     * @const VERSION string
     */
    public const VERSION = '5.42';

    /**
     * access_token
     *
     * @const TOKEN string
     */
    public const TOKEN = 'e06ad89d134065f2030238a36e825c8a775a4720348ed5468182801fab85df9e2117449270b7e6f94d7b0';

    /**
     * ID группы / паблика
     *
     * @const PUBLIC_ID int
     */
    public const PUBLIC_ID = 82614373;

    public static function run()
    {
        return new static;
    }

    /**
     * @param        $text string Текст сообщения
     * @param        $image string|null Полный путь к картинке ex. /var/www/site/img/pic.jpg
     * @param        $tags string|null Хэш тэги через запятую
     * @param string $link ссылка
     *
     * @return bool результат
     * @throws \Exception
     */
    public function postToPublic($text, $image = '', $tags = '', $link = '')
    {

        if ($image) {
            $response = $this->api('photos.getWallUploadServer', ['group_id' => self::PUBLIC_ID]);
            $output = [];
            /** @noinspection PhpUndefinedFieldInspection */
            exec('curl -X POST -F \'photo=@' . $image . '\' \'' . $response->upload_url . '\'', $output);
            $response = json_decode($output[0]);
            $response = $this->api('photos.saveWallPhoto', [
                'group_id' => self::PUBLIC_ID,
                'photo'    => $response->photo,
                'server'   => $response->server,
                'hash'     => $response->hash,
            ]);
        }

        if ($tags) {
            $text .= "\n\n";
            $tag = explode(',', $tags);
            foreach ($tag as $value) {
                $text .= ' #' . str_replace([' ', '-'], '_', trim($value));
            }
        }

        $text = html_entity_decode($text);
        $post = [
            'owner_id'   => -self::PUBLIC_ID,
            'from_group' => 1,
            'message'    => (string)$text,
        ];

        if (!empty($response)) {
            $post['attachments'] = (string)$response[0]->id;
        }
        if ($link) {
            $post['attachments'] = isset($post['attachments']) ? $post['attachments'] . ',' . $link : $link;
        }
        $result = $this->api('wall.post', $post);

        return isset($result->post_id);
    }

    /**
     * Make an API call to https://api.vk.com/method/
     *
     * @param       $method string
     * @param array $query
     *
     * @return string The response, decoded from json format
     * @throws \Exception
     */
    protected function api($method, array $query)
    {
        /* Generate query string from array */
        $parameters = [];
        foreach ($query as $param => $value) {
            $q = $param . '=';
            if (\is_array($value)) {
                $q .= urlencode(implode(',', $value));
            } else {
                $q .= urlencode($value);
            }
            $parameters[] = $q;
        }
        $q = implode('&', $parameters);
        if (\count($query) > 0) {
            $q .= '&'; // Add "&" sign for access_token if query exists
        }
        $url = 'https://api.vk.com/method/' . $method . '?' . $q . 'access_token=' . self::TOKEN . '&v=' . self::VERSION;
        $result = json_decode($this->curl($url));

        if (isset($result->response)) {
            return $result->response;
        }

        return $result;
    }

    /**
     * Делает запрос curl на специльный url
     *
     * @param string $url
     *
     * @return mixed The result of curl_exec() function
     * @throws \Exception
     */
    protected function curl($url)
    {
        $param = parse_url($url);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $param['scheme'] . '://' . $param['host'] . $param['path']);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $param['query']);
        /** @noinspection CurlSslServerSpoofingInspection */
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec($curl);
        if ($errno = curl_errno($curl)) {
            $error_message = curl_strerror($errno);
            throw new \InvalidArgumentException($error_message);
        }
        curl_close($curl);

        return $result;
    }
}
