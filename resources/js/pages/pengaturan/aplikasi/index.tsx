import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import OptimizeClear from './components/optimize-clear';
import LogViewer from './components/log-viewer';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Beranda',
        href: 'beranda',
    },
    {
        title: 'Pengaturan',
        href: 'pengaturan.aplikasi.index',
    },
    {
        title: 'Aplikasi',
        href: 'pengaturan.aplikasi.index',
    },
];

export default function Index({gate}:any) {
     const title = 'Aplikasi'
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={title} />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Card>
                    <CardHeader>
                        <CardTitle className="text-xl">{title}</CardTitle>
                    </CardHeader>
                    <CardContent>
                         {gate.optimizeClear && <OptimizeClear/>}
                         {gate.optimizeClear && <LogViewer/>}
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    )
}
