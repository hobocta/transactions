<?php

namespace Hobocta\Transactions;

class Template
{
    /**
     * @var array
     */
    private $data;
    private $templateName;

    public function __construct($templateName, array $data = array())
    {
        $this->data = $data;
        $this->templateName = $templateName;
    }

    public function render()
    {
        /** @noinspection PhpUnusedLocalVariableInspection */
        $data = $this->data;
        /** @noinspection PhpIncludeInspection */
        require sprintf('%s/../../../templates/%s.php', __DIR__, $this->templateName);
    }
}
