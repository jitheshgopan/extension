<?php namespace Orchestra\Extension;

use Illuminate\Http\Request;
use Illuminate\Contracts\Config\Repository;
use Orchestra\Contracts\Extension\StatusChecker as StatusCheckerContract;

class StatusChecker implements StatusCheckerContract
{
    /**
     * Config instance.
     *
     * @var \Illuminate\Contracts\Config\Repository
     */
    protected $config;

    /**
     * Request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Mode status.
     *
     * @var string|null
     */
    protected $status;

    /**
     * Construct a new Application instance.
     *
     * @param  \Illuminate\Contracts\Config\Repository  $config
     * @param  \Illuminate\Http\Request  $request
     */
    public function __construct(Repository $config, Request $request)
    {
        $this->config  = $config;
        $this->request = $request;
    }

    /**
     * Check current mode is equal given $mode.
     *
     * @param  string  $mode
     *
     * @return bool
     */
    public function is($mode)
    {
        return ($this->mode() === $mode);
    }

    /**
     * Check current mode is not equal given $mode.
     *
     * @param  string  $mode
     *
     * @return bool
     */
    public function isNot($mode)
    {
        return ($this->mode() !== $mode);
    }

    /**
     * Get current mode.
     *
     * @return string
     */
    public function mode()
    {
        if (is_null($this->status)) {
            $this->verifyStatus();
        }

        return $this->status;
    }

    /**
     * Verify safe mode status.
     *
     * @return void
     */
    protected function verifyStatus()
    {
        $config = $this->config->get('orchestra/extension::mode', 'normal');
        $input  = $this->request->input('_mode', $config);

        if ($input == 'safe') {
            $this->enableSafeMode();
        } else {
            $this->disableSafeMode();
        }
    }

    /**
     * Disable safe mode.
     *
     * @return void
     */
    protected function disableSafeMode()
    {
        $this->config->set('orchestra/extension::mode', $this->status = 'normal');
    }

    /**
     * Enable safe mode.
     *
     * @return void
     */
    protected function enableSafeMode()
    {
        $this->config->set('orchestra/extension::mode', $this->status = 'safe');
    }
}
