<?php

namespace Uspacy\SDK\Tests\Services;

use Uspacy\SDK\Tests\TestCase;

class CrmRequisitesAndTemplatesTest extends TestCase
{
    public function test_requisite_templates_and_cards(): void
    {
        $this->sdk->crmRequisites()->getTemplates(['page' => 1, 'list' => 20]);
        $this->assertRequestSent('GET', '/crm/v1/requisites/templates', null, ['page' => 1, 'list' => 20]);

        $this->sdk->crmRequisites()->getCardRequisites(['entity_id' => 5]);
        $this->assertRequestSent('GET', '/crm/v1/requisites', null, ['entity_id' => 5]);

        $this->sdk->crmRequisites()->createCardRequisites(['name' => 'R'], ['entity_id' => 5]);
        $this->assertRequestSent('POST', '/crm/v1/requisites', ['name' => 'R'], ['entity_id' => 5]);

        $this->sdk->crmRequisites()->updateCardRequisites(9, ['name' => 'R2']);
        $this->assertRequestSent('PATCH', '/crm/v1/requisites/9', ['name' => 'R2']);

        $this->sdk->crmRequisites()->deleteCardRequisites(9);
        $this->assertRequestSent('DELETE', '/crm/v1/requisites/9');
    }

    public function test_bank_requisites_nested_paths(): void
    {
        $this->sdk->crmRequisites()->createBankRequisites(9, ['iban' => 'UA...']);
        $this->assertRequestSent('POST', '/crm/v1/requisites/9/bank_requisites', ['iban' => 'UA...']);

        $this->sdk->crmRequisites()->updateBankRequisites(9, 3, ['iban' => 'UA2']);
        $this->assertRequestSent('PATCH', '/crm/v1/requisites/9/bank_requisites/3', ['iban' => 'UA2']);

        $this->sdk->crmRequisites()->deleteBankRequisites(9, 3);
        $this->assertRequestSent('DELETE', '/crm/v1/requisites/9/bank_requisites/3');

        $this->sdk->crmRequisites()->attachBankRequisites(9, ['ref' => 1]);
        $this->assertRequestSent('POST', '/crm/v1/requisites/9/bank_requisites/references/attach-reference', null, ['ref' => 1]);
    }

    public function test_document_templates(): void
    {
        $this->sdk->crmDocumentTemplates()->getDocumentTemplates(['page' => 1]);
        $this->assertRequestSent('GET', '/crm/v1/documents/templates', null, ['page' => 1]);

        $this->sdk->crmDocumentTemplates()->getDocumentTemplatesFields();
        $this->assertRequestSent('GET', '/crm/v1/documents/templates/fields');

        $this->sdk->crmDocumentTemplates()->createTemplate(['name' => 'T']);
        $this->assertRequestSent('POST', '/crm/v1/documents/templates', ['name' => 'T']);

        $this->sdk->crmDocumentTemplates()->deleteTemplate(4);
        $this->assertRequestSent('DELETE', '/crm/v1/documents/templates/4');

        $this->sdk->crmDocumentTemplates()->deleteArrayTemplates([1, 2, 3]);
        $this->assertRequestSent('DELETE', '/crm/v1/documents/templates', null, ['ids' => [1, 2, 3]]);
    }
}
