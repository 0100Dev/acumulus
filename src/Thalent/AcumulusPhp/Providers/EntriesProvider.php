<?php namespace Thalent\AcumulusPhp\Providers;
use Thalent\AcumulusPhp\AcumulusConnector;

/**
 * Class Entries
 * @package Thalent\AcumulusPhp
 */
class EntriesProvider extends AcumulusConnector
{
    /**
     * Supply a valid entryid and get related info. Applies to various types including invoices, expenses and balances. Depending on the type of entry some details may be of less relevance or simply presented as empty.
     * @link https://apidoc.sielsystems.nl/content/entry-get-entry-details
     * @param $entry_id
     * @return $this
     */
    public function getEntryDetails($entry_id)
    {
        $this->apiCall = 'entry/entry_info.php';
        $this->xmlPayload = sprintf('<entryid>%d</entryid>', $entry_id);

        return $this;
    }

    public function updateEntryDetails($entry_id, $paymentstatus = 1, $paymentdate = null, $accountnumber = null)
    {
        if($paymentdate === null) {
            $paymentdate = date('Y-m-d');
        }

        // Validate paymentdate before sending it off
        $datetime = \DateTime::createFromFormat("Y-m-d", $paymentdate);
        if (!$datetime and $datetime->format('Y-m-d') !== $paymentdate) {
            throw new ValidationErrorException("Paymentdate should be in YYYY-MM-DD format");
        }

        $this->apiCall = 'entry/entry_update.php';
        $this->xmlPayload .= sprintf('<entryid>%s</entryid>', $entry_id);
        $this->xmlPayload .= sprintf('<paymentstatus>%d</paymentstatus>', $paymentstatus);
        $this->xmlPayload .= sprintf('<paymentdate>%s</paymentdate>', $paymentdate);
        $this->xmlPayload .= sprintf('<accountnumber>%s</accountnumber>', $accountnumber);

        return $this;
    }
}
