<?

class UI_ae extends ALT\Page {
	public function post() {
		$obj = $this->object();
		$content = $obj->content();

		foreach ($_POST as $k => $v) {
			$content[$k] = $v;
		}

		$obj->layout = json_encode($content, JSON_UNESCAPED_UNICODE);
		$obj->save();
		App::Redirect();

	}

	public function get() {

		$obj = $this->object();

		$e = $this->createE($obj->content());
		$e->add("Label")->input("label");
		$e->add("Link")->input("link");
		$e->add("Icon")->iconpicker("icon");
		
//		$e->add("Color")->input("color")->attr("type","color");
		$e->add("Color")->select("color")->options(["","text-red","text-yellow","text-aqua","text-blue","text-black","text-light-blue","text-green","text-gray","text-navy","text-teal","text-oliver","text-lime","text-orange","text-fuchsia","text-purple","text-maroon"])->attr("id","color1");


		$f = $this->createForm($e);
		$f->action("");
		$this->write($f);

	}
}
?>
<script>
$(function(){
	$("#color1").select2({
		templateResult:function(state){
	 		if (!state.id) { return state.text; }
			
			var v = state.element.value;
			
			return $("<span class='"+v+"'>"+v+"</span>");
		}
	});
	
});</script>