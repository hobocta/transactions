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
        require sprintf('%s/templates/parts/header.php', Environment::getRootDir(), $this->templateName);
        /** @noinspection PhpIncludeInspection */
        require sprintf('%s/templates/%s.php', Environment::getRootDir(), $this->templateName);
        /** @noinspection PhpIncludeInspection */
        require sprintf('%s/templates/parts/footer.php', Environment::getRootDir(), $this->templateName);
    }
}
