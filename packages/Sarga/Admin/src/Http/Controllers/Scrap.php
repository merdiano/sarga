<?php
namespace Sarga\Admin\Http\Controllers;

class Scrap extends \Webkul\Admin\Http\Controllers\Controller
{
    protected $_config;
    /**
     * Contains the keys for which extra filters to show.
     *
     * @var string[]
     */
    protected $extraFilters = ['channels'];

    public function __construct()
    {
        $this->_config = request('_config');
    }

    public function index(){
        $results['extraFilters']  = $this->getNecessaryExtraFilters();

        return view($this->_config['view'],compact('results'));
    }

    /**
     * Get necessary extra details.
     *
     * @return array
     */
    protected function getNecessaryExtraFilters()
    {
        $necessaryExtraFilters = [];

        $checks = [
            'channels'        => core()->getAllChannels(),
            'locales'         => core()->getAllLocales(),
            'customer_groups' => core()->getAllCustomerGroups()
        ];

        foreach ($checks as $key => $val) {
            if (in_array($key, $this->extraFilters)) {
                $necessaryExtraFilters[$key] = $val;
            }
        }

        return $necessaryExtraFilters;
    }
}