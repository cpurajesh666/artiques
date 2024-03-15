<?php

namespace Botble\Report\Exports;

use Botble\Contact\Enums\ContactStatusEnum;
use Botble\Table\Supports\TableExportHandler;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class GuestUsersExport extends TableExportHandler
{
    /**
     * {@inheritDoc}
     */
    protected function afterSheet(AfterSheet $event)
    {
        parent::afterSheet($event);

        $totalRows = $this->collection->count() + 1;

        $event->sheet
            ->getDelegate()
            ->getStyle('B1:B' . $totalRows)
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);

        $event->sheet
            ->getDelegate()
            ->getStyle('C1:C' . $totalRows)
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_LEFT);
    }
}
