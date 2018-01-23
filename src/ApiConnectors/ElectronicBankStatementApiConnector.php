<?php

namespace PhpTwinfield\ApiConnectors;

use PhpTwinfield\DomDocuments\ElectronicBankStatementDocument;
use PhpTwinfield\ElectronicBankStatement;
use PhpTwinfield\Exception;
use Webmozart\Assert\Assert;

/**
 * A facade to make interaction with the the Twinfield service easier when trying to retrieve or send information about
 * Electronic Bank Statements.
 */
class ElectronicBankStatementApiConnector extends BaseApiConnector
{
    /**
     * @param ElectronicBankStatement $statement
     * @throws Exception
     */
    public function send(ElectronicBankStatement $statement): void
    {
        $this->sendAll([$statement]);
    }

    /**
     * @param ElectronicBankStatement[] $statements
     * @throws Exception
     */
    public function sendAll(array $statements)
    {
        Assert::allIsInstanceOf($statements, ElectronicBankStatement::class);

        foreach ($this->getProcessXmlService()->chunk($statements) as $chunk) {

            $document = new ElectronicBankStatementDocument();

            foreach ($chunk as $statement) {
                $document->addStatement($statement);
            }

            $this->getProcessXmlService()->sendDocument($document);
        }
    }
}
