import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import beranda from '@/../images/ilustration/beranda.svg';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Beranda',
        href: 'beranda',
    },
];

export default function Beranda() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Beranda" />
                <div className="flex items-center justify-center p-6">
                    <div className="p-8 max-w-4xl w-full grid grid-cols-1 md:grid-cols-2 gap-1">
                        <div className="flex flex-col justify-center">
                            <h1 className="text-3xl md:text-4xl font-bold mb-4">
                                Selamat Datang
                            </h1>
                            <p className="text-lg">
                                DI APLIKASI PELAYANAN PBB
                            </p>
                        </div>
                        <div className="flex items-center justify-center">
                        <img
                            src={beranda}
                            alt="Ilustrasi Pajak"
                            className="w-full max-h-80 object-contain"
                        />
                        </div>
                    </div>
                </div>
        </AppLayout>
    );
}
