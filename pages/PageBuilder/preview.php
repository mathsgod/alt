<?

class PageBuilder_preview extends App\Page
{
    public function post()
    {
        $html = [];
        foreach ($_POST as $widget) {
            $html[] = App\PageBuilder::RenderWidget($widget);
        }

        return ["code" => 200, "html" => implode("", $html)];
    }

    public function get()
    {
        $obj = $this->object();
        $this->write($obj->render());
    }
}