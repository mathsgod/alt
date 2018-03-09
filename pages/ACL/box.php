<?

class ACL_box extends App\Page {

	public function get($usergroup_id, $path, $module, $remove) {

		if ($remove) {


			$w = [];
			$w[] = ["path=?", $path];
			$w[] = ["usergroup_id=?", $usergroup_id];
			foreach (App\ACL::find($w) as $a) {
				$a->delete();
			}
			return;
		}
		$acl = new App\ACL();
		$acl->module = $module;
		$acl->usergroup_id = $usergroup_id;
		$acl->value = "allow";
		$acl->path = $path;
		$acl->save();

	}
}
