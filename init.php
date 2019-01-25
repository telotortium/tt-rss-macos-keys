<?php
class MacOS_Keys extends Plugin {

    private $host;

    function about() {
        return array(1.0,
            "Remap keybindings containing Ctrl to use Cmd instead on MacOS",
            "telotortium");
    }

    function init($host) {
        $this->host = $host;

        $host->add_hook($host::HOOK_HOTKEY_MAP, $this);
    }

    function hook_hotkey_map($hotkeys) {
        // Only overwrite keybindings on macOS (formerly "Mac OS X").
        if (!preg_match(
                '/.*Mozilla\/5\.0 \(.*Mac OS X.*\).*/',
                $_SERVER['HTTP_USER_AGENT'])) {
            return $hotkeys;
        }

        foreach (array_keys($hotkeys) as $key) {
            if ($key[0] !== "^") {
               continue;
            }

            $parts = explode("|", $key);
            $parts[0] = preg_replace('/^\^/', "%", $parts[0]);
            if (count($parts) > 1) {
              $parts[1] = preg_replace('/^Ctrl([+-])/', 'Cmd\1', $parts[1]);
            }
            $newkey = implode("|", $parts);
            $hotkeys[$newkey] = $hotkeys[$key];
            unset($hotkeys[$key]);
        }
        return $hotkeys;
    }

    function api_version() {
        return 2;
    }

}
?>
