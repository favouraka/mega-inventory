<?php

namespace App\Filament\Pages;

use App\Models\Order;
use App\Models\Sale;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\IconEntry\IconEntrySize;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\Section as ComponentsSection;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\TextEntry\TextEntrySize;
use Filament\Infolists\Infolist;
use Filament\Pages\Page;
use Filament\Support\Colors\Color;
use PHPUnit\Event\Code\Test;
use Fpdf\Fpdf;

class PrintReciept extends Page
{
    public string $reference;
    public Order $order;
    protected $queryString = [
        'reference'
    ];

    public function mount()
    {
        $this->order = Order::whereReference($this->reference)->first();
    }


    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.print-reciept';

    protected static bool  $shouldRegisterNavigation = false;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print')->icon('heroicon-o-printer')->color(Color::Blue)->action(fn () => $this->dispatch('print-file'))->hidden(true),
            Action::make('download')->icon('heroicon-o-cloud-arrow-down')->color(Color::Fuchsia)->action(fn () => $this->downloadReciept())->hidden(false),
            Action::make('share')->icon('heroicon-o-share')->color(Color::Green)->action(fn () => $this->shareReceipt()),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
                    ->record($this->order)
                    ->columns(2)
                    ->schema([
                        ImageEntry::make('store.logo')->height(120)->hiddenLabel(true)->columnSpanFull(),
                        TextEntry::make('reference')
                                ->color('success'),
                        TextEntry::make('total_paid')
                                ->columnSpanFull()
                                ->default(fn (Order $record) => $record->sales->sum(fn ($sale) => $sale->sale_price * $sale->quantity))
                                ->view('components.price-text'),
                        ComponentsSection::make('Store Information')
                                ->schema([
                                    TextEntry::make('store.name'),
                                    TextEntry::make('store.address')->label('Address'),
                                ]),
                        ComponentsSection::make('Sales Information')
                                ->schema([
                                    RepeatableEntry::make('sales')
                                        ->columns(2)
                                        ->label('')
                                        ->schema([
                                            TextEntry::make('product_details')->default(fn (Sale $record) => $record->inventory->product->title . ' x ' . $record->quantity),
                                            TextEntry::make('amount')->default(fn (Sale $record) => '₦'.$record->sale_price * $record->quantity),
                                        ])
                                ])
                    ]);
    }

    public function generateRecieptPDF()
    {
        $pdf = new Fpdf();
        $pdf->AddPage();

        // Add store logo
        //
        $pdf->Image(asset('storage/'.$this->order->store->logo), 10, 10, 30);

        // Add space after the logo
        $pdf->Ln(20);

        // Set font
        $pdf->SetFont('Arial', 'B', 16);

        // Title
        $pdf->Cell(0, 10, 'Receipt', 0, 1, 'R');

        // Store Information
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Store Information', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Name: ' . $this->order->store->name, 0, 1);
        $pdf->Cell(0, 10, 'Address: ' . $this->order->store->address, 0, 1);

        // Add horizontal line
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());

        // Add space after the line
        $pdf->Ln(10);

        // Order Information
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Order Information', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'Reference: ' . $this->order->reference, 0, 1);
        $pdf->Cell(0, 10, 'Customer: ' . $this->order->customer_name, 0, 1);
        $pdf->Cell(0, 10, 'Phone: ' . $this->order->customer_phone, 0, 1);
        $pdf->Cell(0, 10, 'Date & Time: ' . $this->order->created_at->format('F j, Y, g:i A'), 0, 1);

        // Add horizontal line
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());

        // Add space after the line
        $pdf->Ln(10);

        // Sales Information
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'Sales Information', 0, 1);
        $pdf->SetFont('Arial', '', 12);

        // Add space before the table
        $pdf->Ln(5);

        // Table header
        $pdf->SetFillColor(220, 220, 220); // Light grey color
        $pdf->Cell(90, 10, 'Product', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Quantity', 1, 0, 'C', true);
        $pdf->Cell(70, 10, 'Price', 1, 1, 'C', true);

        // Table content
        foreach ($this->order->sales as $sale) {
            $pdf->Cell(90, 10, $sale->inventory->product->title, 1);
            $pdf->Cell(30, 10, $sale->quantity, 1);
            $pdf->Cell(70, 10, 'NGN ' . ($sale->sale_price * $sale->quantity), 1);
            $pdf->Ln();
        }

        // Add horizontal line
        $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY());

        // Add space after the line
        $pdf->Ln(10);

        // Total
        $pdf->SetFont('Arial', 'B', 24);
        $pdf->Cell(0, 20, 'Total: NGN ' . $this->order->sales->sum(fn ($sale) => $sale->sale_price * $sale->quantity), 0, 1);

        // Output PDF to a string
        return $pdfContent = $pdf->Output('S');

    }


    /**
     * Download the receipt as a PDF file.
     *
     * This method generates a PDF receipt using the generateRecieptPDF method
     * and returns it as a downloadable file response.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    protected function downloadReciept()
    {
        $pdfContent = $this->generateRecieptPDF();
        // Use Livewire's download method
        return response()->streamDownload(function () use ($pdfContent) {
            echo $pdfContent;
        }, 'receipt_' . $this->order->reference . '.pdf');

    }

    protected function shareReceipt()
    {
        $pdfContent = $this->generateRecieptPDF();
        $this->dispatch('share-receipt', fileString: base64_encode($pdfContent));
    }
}
