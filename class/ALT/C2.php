<?php

namespace ALT;

use Exception;
use My\Func;

class C2 extends \P\HTMLElement
{
	private $_format;
	public $cell;
	public $label;
	public $c_tpl;

	public function cell()
	{
		return $this->cell;
	}

	public function __construct($tag)
	{
		parent::__construct($tag);
		$this->cell = p();
		$this->c_tpl = p();
	}

	public function gf($gf)
	{
		foreach ($this->cell as $cell) {
			p($cell)->attr("data-gf", $gf);

			if ($object = p($cell)->data("object")) {
				$field = p($cell)->attr("data-field");
				$gf_obj = Func::_($field)->call($object);
				p($cell)->text(Func::_($gf)->call($gf_obj));
			}

		}
		return $this;
	}

	public function iconpicker($field)
	{
		$p = new \BS\ButtonCollection;

		foreach ($this->cell as $cell) {
			$btn = new \BS\Button();
			$p->append($btn);
			$btn->attributes["data-iconset"] = "fontawesome";
			$btn->attributes["role"] = "iconpicker";
			$btn->attributes["data-rows"] = 10;
			$btn->attributes["data-cols"] = 10;
			$btn->attributes["name"] = $field;

			p($cell)->append($btn);

			if ($object = p($cell)->data("object")) {

				$btn->attributes["data-icon"] = \My\Func::_($field)->call($object);
			}

		}
		return $p;
	}

	public function ace($field, $mode)
	{
		$p = p();

		foreach ($this->cell as $cell) {
			$e = p("textarea");
			$e->css("height", "400px");

			$e->attr("ace", true);
			$e->attr('data-field', $field);
			$e->attr('name', $field);

			if ($mode) {
				$e->attr("ace-mode", $mode);
			}

			if ($object = p($cell)->data("object")) {
				$e->data("object", $object);
				$e->text(is_object($object) ? $object->$field : $object[$field]);

				if ($this->callback) {
					call_user_func($this->callback, $object, $e[0]);
				}
			}

			$cell->append($e);
		}


		if ($this->createTemplate) {
			$textarea = p("textarea");
			$textarea->attr("name", $field);
			$textarea->attr("data-field", $field);

			$p[] = $textarea[0];
			$this->c_tpl[] = $textarea[0];
		}

		return $p;
	}



	public function alink($uri)
	{
		foreach ($this->cell as $cell) {
			if ($object = p($cell)->data("object")) {
				$field = p($cell)->attr("data-field");
				$next_obj = \My\Func::_($field)->call($object);
				if (is_object($next_obj)) {
					$object = $next_obj;
				}

				$href = $object->uri($uri);

				$a = p("a")->attr('href', $href);
				$a->append(p($cell)->contents());
				$a->appendTo(p($cell));
			}
		}
		return $this;
	}

	public function width($width)
	{
		p($this)->css("width", "{$width}px");
		return $this;
	}

	public function attr($name, $value)
	{
		foreach ($this->cell as $cell) {
			if ($object = p($cell)->data("object")) {
				if ($value instanceof \Closure) {
					$value = $value->call($object);
				}
			}
			p($cell)->attr($name, $value);
		}
		return $this;
	}

	public function format($callback, $params = null)
	{
		foreach ($this->cell as $cell) {
			if ($object = p($cell)->data("object")) {
				$content = p($cell)->html();
				if (is_array($callback)) {
					$content = call_user_func($callback, $content, $params);
				} else {
					$content = \My\Func::_($callback)->call($content);
				}

				p($cell)->html($content);
			}
		}
		return $this;
	}

	public function password($field)
	{
		$p = new \P\InputCollection;
		foreach ($this->cell as $cell) {
			$input = p("bs-input")->appendTo($cell);
			$input->attr("type", "password");
			$input->attr("name", $field);
			$input->attr("data-field", $field);

			if ($object = p($cell)->data("object")) {
				$input->data("object", $object);

				if ($this->callback) {
					call_user_func($this->callback, $object, $input[0]);
				}
			}


			$p[] = $input[0];

		}

		if ($this->createTemplate) {
			$input = p("input");
			$input->addClass('form-control');
			$input->attr("name", $field);
			$input->attr("data-field", $field);

			$p[] = $input[0];


			$this->c_tpl[] = $input[0];

		}
		return $p;
	}

	public function input($field)
	{
		$p = new \P\InputCollection;
		foreach ($this->cell as $cell) {
			try {
				$input = p("input")->appendTo($cell);
				$input->attr("is", "alt-input");
				$input->attr("name", $field);
				$input->attr("data-field", $field);

				if ($object = p($cell)->data("object")) {
					$input->data("object", $object);
					$input->attr("value", is_object($object) ? $object->$field : $object[$field]);

					if ($this->callback) {
						call_user_func($this->callback, $object, $input[0]);
					}
				}

				$p[] = $input[0];
			} catch (Exception $e) {
				$cell->append("<p class='form-control-static'>" . $e->getMessage() . "</p>");
			}
		}

		if ($this->createTemplate) {
			$input = p("div");
			$input->attr("is", "alt-input");
			$input->attr("name", $field);
			$input->attr("data-field", $field);
			$input->attr("value", $this->default[$field]);

			$p[] = $input[0];

			$this->c_tpl[] = $input[0];

		}
		return $p;
	}

	public function roxyfileman($field)
	{
		$p = $this->input($field);
		$p->attr("is", "roxyfileman");
		return $p;
	}

	public function ckeditor($field)
	{
		$p = p();

		foreach ($this->cell as $cell) {
			try {
				$textarea = p("textarea")->appendTo($cell);
				$textarea->attr("is", "ckeditor");
				$textarea->attr('data-field', $field);
				$textarea->attr('name', $field);
				$textarea->addClass('form-control');

				if ($object = p($cell)->data("object")) {
					$textarea->data("object", $object);

					//$textarea->text(is_object($object) ? $object->$field : $object[$field]);
					$textarea->attr("data", is_object($object) ? $object->$field : $object[$field]);

					if ($this->callback) {
						call_user_func($this->callback, $object, $textarea[0]);
					}
				}
				$p[] = $textarea[0];
			} catch (Exception $e) {
				$cell->append("<p class='form-control-static'>" . $e->getMessage() . "</p>");
			}
		}


		if ($this->createTemplate) {
			$textarea = p("ckeditor");
			$textarea->addClass('form-control');
			$textarea->attr("name", $field);
			$textarea->attr("data-field", $field);

			$p[] = $textarea[0];
			$this->c_tpl[] = $textarea[0];
		}

		return $p;
	}

	public function textarea($field)
	{
		$p = p();

		foreach ($this->cell as $cell) {
			try {
				$textarea = p("textarea")->appendTo($cell);
				$textarea->attr('data-field', $field);
				$textarea->attr('name', $field);
				$textarea->addClass('form-control');

				if ($object = p($cell)->data("object")) {
					$textarea->data("object", $object);

					$textarea->text(is_object($object) ? $object->$field : $object[$field]);

					if ($this->callback) {
						call_user_func($this->callback, $object, $textarea[0]);
					}
				}
				$p[] = $textarea[0];
			} catch (Exception $e) {
				$cell->append("<p class='form-control-static'>" . $e->getMessage() . "</p>");
			}
		}


		if ($this->createTemplate) {
			$textarea = p("textarea");
			$textarea->addClass('form-control');
			$textarea->attr("name", $field);
			$textarea->attr("data-field", $field);

			$p[] = $textarea[0];
			$this->c_tpl[] = $textarea[0];
		}

		return $p;
	}

	public function select($field)
	{
		$p = new \P\SelectCollection();

		foreach ($this->cell as $cell) {
			$select = p("select")->appendTo($cell);
			$select->addClass("form-control");
			$select->attr("data-field", $field);
			$select->attr("name", $field);

			if ($object = p($cell)->data("object")) {
				$select->data("object", $object);
				$select->attr("data-value", is_object($object) ? $object->$field : $object[$field]);
				if ($this->callback) {
					call_user_func($this->callback, $object, $select[0]);
				}
			}

			$p[] = $select[0];
		}

		if ($this->createTemplate) {
			$select = p("select");
			$select->addClass("form-control");
			$select->attr("data-field", $field);
			$select->attr("name", $field);

			$p[] = $select[0];
			$this->c_tpl[] = $select[0];
		}

		return $p;
	}

	public function Xeditable($index, $type = "text")
	{
		$p = p();
		foreach ($this->cell as $cell) {
			if ($type == "text") {
				$a = new \Xeditable\Text();
				$a->setAttribute("index", $index);
			} elseif ($type == "textarea") {
				$a = new \Xeditable\Textarea();
				$a->setAttribute("index", $index);
			} elseif ($type == "date") {
				$a = new \Xeditable\Date();
				$a->setAttribute("index", $index);
			} elseif ($type == "select") {
				$a = new \Xeditable\Select();
			} elseif ($type == "datetime") {
				$a = new \Xeditable\DateTime();
				$a->setAttribute("index", $index);
			} else {
				throw new \Exception("Xeditable type $type not found");
			}
			$a->appendTo($cell);

			if ($object = p($cell)->data("object")) {
				if ($type != "select") {
					$a->text(is_object($object) ? $object->$index : $object[$index]);
				}

				$a->setAttribute("data-pk", $object->id());
				$a->setAttribute("data-url", $object->uri() . "?xeditable");
			}

			$a->setAttribute("name", $index);
			$a->setAttribute("data-name", $index);

			$p[] = $a;
		}

		return $p;
	}

	public function ws($value = "pre")
	{
		$this->css("white-space", $value);
		return $this;
	}

	public function a($field)
	{
		$p = new \P\AnchorCollection;
		foreach ($this->cell as $cell) {
			$a = p("a")->appendTo($cell);
			$a->attr("data-field", $field);

			if ($object = p($cell)->data("object")) {
				$a->data("object", $object);
				$a->text(Func::_($field)->call($object));
			}
			$p[] = $a[0];

			if ($this->callback) {
				call_user_func($this->callback, $object, $a[0]);
			}
		}
		return $p;
	}

	public function email($field)
	{
		$p=$this->input($field);
		$p->attr("is","alt-email");
		return $p;
	}

	public function button()
	{
		$p = new \BS\ButtonCollection;
		foreach ($this->cell as $cell) {
			$btn = new \App\UI\Button();
			$btn->classList->add("btn-xs");
			p($cell)->append($btn);
			if ($object = p($cell)->data("object")) {
				p($btn)->data("object", $object);
			}

			$p[] = $btn;
		}
		return $p;
	}

	public function tokenField($field, $options)
	{

		$p = new \P\SelectCollection();
		foreach ($this->cell as $cell) {

			$input = p("input")->appendTo($cell);
			$input->attr("type", "hidden");
			$input->attr("name", $field);

			$e = p("select2")->appendTo($cell);
			$e->attr("name", $field . "[]");
			$e->attr("data-tags", "true");
			$e->attr("multiple", true);


			$data = [];
			$value = [];
			if ($object = p($cell)->data("object")) {
				$value = is_object($object) ? $object->$field : $object[$field];
				if (is_string($value)) {
					$value = explode(",", $value);
				}

				foreach ($value as $v) {
					$data[] = [
						"id" => $v,
						"text" => $v,
						"selected" => true
					];
				}
			}
			foreach ($options as $v) {
				if (!in_array($v, $value)) {
					$data[] = [
						"id" => $v,
						"text" => $v
					];
				}
			}


			$e->attr("data-data", $data);
			$p[] = $e;
		}

		if ($this->createTemplate) {
			$e = p("select2");
			$e->attr("name", $field);
			$e->attr("data-tags", "true");
			$e->attr("multiple", true);

			$p[] = $e;
			$this->c_tpl[] = $e;
		}

		return $p;
	}

	public function inputSelect($field)
	{
		$p = new \BS\InputSelectCollection();
		foreach ($this->cell as $cell) {

			$is = new InputSelect();
			$is->setAttribute("data-field", $field);
			$is->setAttribute("name", $field);
			$cell->append($is);

			if ($object = p($cell)->data("object")) {
				$is->setAttribute("value", is_object($object) ? $object->$field : $object[$field]);

				if ($this->callback) {
					call_user_func($this->callback, $object, $is);
				}
			}

			$p[] = $is;
		}

		if ($this->createTemplate) {
			$is = new \BS\InputSelect();
			p($is)->find("input")->attr("data-field", $field)->attr("name", $field);
			$p[] = $is;
			$this->c_tpl[] = $is;
		}
		return $p;
	}

	public function checkboxes($field)
	{
		$p = p();
		foreach ($this->cell as $cell) {
		}
		return $p;
	}

	public function checkbox($field)
	{
		//		$p = $this->input($field);
		//	$p->attr("type", "hidden");

		$p = p();
		foreach ($this->cell as $cell) {

			$input = p("input")->appendTo($cell);
			$input->attr("type", "hidden");
			$input->attr("data-field", $field);
			$input->attr("name", $field);
			$input->val(0);
			call_user_func($this->callback, null, $input[0]);


			$cb = new \BS\CheckBox();
			p($cell)->append($cb);

			$input = $cb->find("input");
			$input->attr("is", "icheck");
			$input->attr("name", $field);
			$input->attr("data-field", $field);
			$input->val(1);

			if ($object = p($cell)->data("object")) {
				$value = is_object($object) ? $object->$field : $object[$field];
				if ($value) {
					$input->attr("checked", true);
				}

				if ($this->callback) {
					call_user_func($this->callback, $object, $cb->find("input")[0]);
				}
			}

			$p[] = $cb[0];
		}

		if ($this->createTemplate) {
			$cb = new \BS\CheckBox();
			$input = $cb->find("input");
			$input->attr("name", $field);
			$input->attr("data-field", $field);
			$input->addClass("iCheck");
			$input->val(1);
			$this->c_tpl[] = $cb;
		}

		return $p;
	}

	public function colorpicker($index)
	{
		return $this->input($index)->addClass("cp");
	}

	public function date($field)
	{
		$p = new \P\InputCollection;
		foreach ($this->cell as $cell) {

			$div = p("div")->appendTo($cell);
			$div->attr("is", "alt-date");
			$div->attr("name", $field);
			$div->attr("data-field", $field);
			if ($object = p($cell)->data("object")) {
				$div->data("object", $object);
				$div->attr("value", is_object($object) ? $object->$field : $object[$field]);

				if ($this->callback) {
					call_user_func($this->callback, $object, $div[0]);
				}
				$p[] = $div[0];
			}

		}

		if ($this->createTemplate) {

			$div = p("div")->attr("is", "alt-date");
			$div->attr("name", $field);
			$div->attr("data-field", $field);
			$p[] = $div[0];
			$this->c_tpl[] = $div[0];
		}
		return $p;
	}

	public function time($field)
	{
		$p = $this->datetime($field);
		$p->attr("format", "HH:mm");
		$p->attr("icon", "far fa-clock");
		return $p;
	}

	public function datetime($field = null)
	{
		$p = new \P\InputCollection;
		foreach ($this->cell as $cell) {

			$div = p("div")->appendTo($cell);
			$div->attr("is", "alt-datetime");
			$div->attr("name", $field);
			$div->attr("data-field", $field);
			if ($object = p($cell)->data("object")) {
				$div->data("object", $object);
				$div->attr("value", is_object($object) ? $object->$field : $object[$field]);

				if ($this->callback) {
					call_user_func($this->callback, $object, $div[0]);
				}
				$p[] = $div[0];
			}

		}

		if ($this->createTemplate) {

			$div = p("div")->attr("is", "alt-datetime");
			$div->attr("icon", "far fa-clock-alt");
			$div->attr("name", $field);
			$div->attr("data-field", $field);
			$p[] = $div[0];
			$this->c_tpl[] = $div[0];
		}
		return $p;
	}

	public function multiSelect($field)
	{
		$p = new \P\SelectCollection();
		foreach ($this->cell as $cell) {
			$input = p("input")->appendTo($cell);
			$input->attr("type", "hidden");
			$input->attr("data-field", $field);
			$input->attr("name", $field);

			$select = p("select")->appendTo($cell);
			$select->attr("is", "alt-multiselect");
			$select->attr("data-field", $field);
			$select->attr("name", $field);

			if ($object = p($cell)->data("object")) {
				$select->data("object", $object);

				$value = is_object($object) ? $object->$field : $object[$field];
				if (is_string($value)) {
					$value = explode(",", $value);
				}
				$select->attr("data-value", $value);
				if ($this->callback) {
					call_user_func($this->callback, $object, $input[0]);
					call_user_func($this->callback, $object, $select[0]);
				}
			}

			$select->attr("name", $field . "[]");

			$p[] = $select[0];
		}
		return $p;
	}

	public function multiSelectPicker($field)
	{
		$p = new \P\SelectCollection();

		foreach ($this->cell as $cell) {
			$select = p("select")->appendTo($cell);
			$select->addClass("selectpicker");
			$select->attr("data-live-search", "true");
			$select->attr("data-field", $field);
			$select->attr("data-actions-box", "true");
			$select->attr("name", $field . "[]");
			$select->attr("multiple", true);
            //$select->attr("data-width","fit");

			if ($object = p($cell)->data("object")) {
				$select->data("object", $object);
				$select->attr("data-value", is_object($object) ? $object->$field : $object[$field]);
				if ($this->callback) {
					call_user_func($this->callback, $object, $select[0]);
				}
			}

			$p[] = $select[0];
		}

		if ($this->createTemplate) {
			$select = p("select");
			$select->addClass("form-control");
			$select->attr("data-live-search", "true");
			$select->attr("data-field", $field);
			$select->attr("data-actions-box", "true");
			$select->attr("name", $field . "[]");
			$select->attr("multiple", true);


			$p[] = $select[0];
			$this->c_tpl[] = $select[0];
		}

		return $p;
	}

	public function selectPicker($field)
	{
		$p = new \P\SelectCollection();

		foreach ($this->cell as $cell) {
			$select = p("select")->appendTo($cell);
			$select->addClass("form-control selectpicker");
			$select->attr("data-live-search", "true");
			$select->attr("data-field", $field);
			$select->attr("name", $field);

			if ($object = p($cell)->data("object")) {
				$select->data("object", $object);
				$select->attr("data-value", is_object($object) ? $object->$field : $object[$field]);
				if ($this->callback) {
					call_user_func($this->callback, $object, $select[0]);
				}
			}

			$p[] = $select[0];
		}

		if ($this->createTemplate) {
			$select = p("select");
			$select->addClass("form-control selectpicker");
			$select->attr("data-field", $field);
			$select->attr("name", $field);

			$p[] = $select[0];
			$this->c_tpl[] = $select[0];
		}

		return $p;
	}

	public function multiSelect2($field)
	{
		foreach ($this->cell as $cell) {
			$input = p("input")->appendTo($cell);
			$input->attr("type", "hidden");
			$input->attr("name", $field);

			if ($object = p($cell)->data("object")) {
				if ($this->callback) {

					call_user_func($this->callback, $object, $input[0]);
				}
			}
		}

		$select = $this->select2($field);
		$select->attr("multiple", true);
		$select->attr("name", $field . "[]");
		return $select;
	}

	public function select2($field)
	{
		$p = new \P\SelectCollection();

		foreach ($this->cell as $cell) {
			$select = p("select")->appendTo($cell);
			$select->attr("is", "select2");
			$select->attr("data-field", $field);
			$select->attr("name", $field);

			if ($object = p($cell)->data("object")) {
				$select->data("object", $object);
				try {
					$data_value = is_object($object) ? $object->$field : $object[$field];
					if (!is_array($data_value)) {
						$data_value = explode(",", $data_value);
					}

					$select->attr("data-value", $data_value);
				} catch (\Exception $e) {

				}

				if ($this->callback) {
					call_user_func($this->callback, $object, $select[0]);
				}
			}

			$p[] = $select[0];
		}

		if ($this->createTemplate) {
			$select = p("select");
			$select->addClass("select2 form-control");
			$select->attr("data-field", $field);
			$select->attr("name", $field);
			$this->c_tpl[] = $select;

			$p[] = $select[0];
		}
		return $p;
	}

	public function helpBlock($text)
	{
		$p = p();
		foreach ($this->cell as $cell) {
			$block = p("p")->appendTo($cell);
			$block->addClass("help-block");
			$block->html($text);
			$p[] = $block[0];
		}

		if ($this->createTemplate) {
			$block = p("p");
			$block->addClass("help-block");
			$block->html($text);
			$this->c_tpl[] = $block[0];
		}

		return $p;
	}

	public function img($field)
	{
		$p = p();
		foreach ($this->cell as $cell) {
			$img = p("img")->appendTo($cell);
			$img->attr("data-field", $field);
			if ($object = p($cell)->data("object")) {
				$img->attr("src", is_object($object) ? $object->$field : $object[$field]);
			}
			$p[] = $img[0];
		}
		return $p;
	}

	public function __toString()
	{
		if ($this->c_tpl) {
			$this->attributes["c-tpl"] = (string)$this->c_tpl;
		}
		return parent::__toString();
	}

}
