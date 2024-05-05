<?php

namespace App\Service;
use App\Models\Invoice;

class InvoiceService {

    public function findInvoice(Invoice $invoice)
    {
        return $invoice;
    }

    public function createInvoice($data) {
        // Create invoice
    }
}
?>