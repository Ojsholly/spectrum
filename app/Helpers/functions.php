<?php

    function log_activity($auid, $title, $action, $suid = null, $ip_address)
    {
        $activity = [
            'auid' => $auid,
            'suid' => $suid,
            'title' => $title,
            'action'=> $action,
            'ip_address' => $ip_address,
            'created_at'=> now()
        ];

        // $suid is the uuid for the account being acted on while $auid is the uuid for the person performing the action.

        DB::table('audit_logs')->insert($activity);
    }

?>
