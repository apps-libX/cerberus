<?php
/**
 * CerberusViewfinderTrait.php
 * Modified from https://github.com/rydurham/Sentinel
 * by anonymous on 13/01/16 1:22.
 */

namespace Cerberus\Traits;

use Redirect;
use Response;
use View;

trait CerberusViewfinderTrait
{
    /**
     * Before returning an HTML view, we need to make sure the developer has not
     * specified that we only use JSON responses.
     *
     * @param       $view
     * @param array $payload
     *
     * @return Response
     */
    public function viewFinder($view, $payload = [])
    {

        // Check the config for enabled views
        if (config('cerberus.views_enabled')) {
            // Views are enabled.
            return View::make($view)->with($payload);
        } else {
            // Check the payload for paginator instances.
            foreach ($payload as $name => $item) {
                if ($item instanceof \Illuminate\Pagination\Paginator) {
                    $payload[$name] = $item->getCollection();
                }
            }

            return Response::json($payload);
        }
    }
}
