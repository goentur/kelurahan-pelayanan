<?php

namespace App\Traits\Pdf;

trait PdfResponse
{
    public function pdfResponse($content)
    {
        return response($content)
            ->header('Content-Type', 'application/pdf')
            ->header('Cache-Control', 'private, max-age=0, must-revalidate')
            ->header('Pragma', 'public');
    }

    public function pdfStreamResponse($content)
    {
        return response()
            ->stream(
                function () use ($content) {
                    echo $content;
                },
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Cache-Control' => 'private, max-age=0, must-revalidate',
                    'Pragma' => 'public',
                ]
            );
    }
}
