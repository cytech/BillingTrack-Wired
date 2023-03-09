<?php

/**
 * This file is part of BillingTrack.
 *
 *
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BT\Composers;

class SkinComposer
{
    public function compose($view)
    {
        $defaultSkin = json_decode('{"headBackground":"purple","headClass":"light","sidebarMode":"open"}',true);

        $skin = (config('bt.skin') ? json_decode(config('bt.skin'),true) : $defaultSkin);

        //default BS5 light and dark color-modes
        if ($skin['headBackground'] != 'light' && $skin['headBackground'] != 'dark') {
            $skin['headBackground'] = $skin['headBackground'] . '-' . $skin['headClass'];
        }

        if ($skin['sidebarMode'] == 'open') {
            $skin['sidebarMode'] = 'full'; //sidebar-full does not exist in adminltev4, defaults to normal
        }

        $view->with('headBackground', $skin['headBackground']);
        $view->with('sidebarMode', $skin['sidebarMode']);
    }
}
