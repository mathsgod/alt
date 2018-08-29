<?

class Module_install extends ALT\Page
{
    public function get()
    {

        $this->write("test");

        $this->getGitHub();
    }

    public function getGitHub()
    {
        $options = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
                "header" => "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36\r\n".
                "Accept: application/json"
            ]
        ];

        $content = file_get_contents("https://api.github.com/search/repositories?q=user:hostlink", false, stream_context_create($options));
        outp($content);
        die();
        $ret = json_decode($content, true);

        return $ret;
    }

}