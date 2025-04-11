import LoadingData from '@/components/data-table/loading-data';
import NoData from '@/components/data-table/no-data';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { alertApp } from '@/components/utils';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import axios from 'axios';
import { useEffect, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Beranda',
        href: 'beranda',
    },
    {
        title: 'Dashboard',
        href: 'dashboard.realisasi.index',
    },
    {
        title: 'Realisasi',
        href: 'dashboard.realisasi.index',
    },
];

export default function Index() {
    const title = "Realisasi"
    const [loading, setLoading] = useState(false);
    const [dataTable, setDataTable] = useState<[]>([]);
    
    useEffect(() => {
        getData();
    }, []);

    const getData = async () => {
        setLoading(true);
        try {
            const response = await axios.post(route('dashboard.realisasi.data'));
            setDataTable(response.data);
        } catch (error:any) {
            alertApp(error.message, 'error');
        } finally {
            setLoading(false);
        }
    };
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={title} />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Card>
                    <CardHeader>
                        <CardTitle className="text-xl">{title}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <table className="w-full text-left text-sm border-collapse border">
                            <thead className='text-center'>
                                <tr className="uppercase leading-normal">
                                    <th className="p-2 border w-1" rowSpan={2}>BUKU</th>
                                    <th className="p-2 border" colSpan={2}>BAKU AWAL</th>
                                    <th className="p-2 border" colSpan={2}>BAKU JALAN</th>
                                    <th className="p-2 border" colSpan={2}>REALISASI PENYAMPAIAN</th>
                                    <th className="p-2 border" colSpan={2}>REALISASI PEMBAYARAN</th>
                                </tr>
                                <tr className="uppercase leading-normal">
                                    <th className="p-2 border">OBJEK PAJAK</th>
                                    <th className="p-2 border w-1">KETETAPAN</th>
                                    <th className="p-2 border">OBJEK PAJAK</th>
                                    <th className="p-2 border w-1">KETETAPAN</th>
                                    <th className="p-2 border">OBJEK PAJAK</th>
                                    <th className="p-2 border w-1">KETETAPAN</th>
                                    <th className="p-2 border">OBJEK PAJAK</th>
                                    <th className="p-2 border w-1">KETETAPAN</th>
                                </tr>
                            </thead>
                            <tbody className="font-light">
                                {loading && <LoadingData colSpan={9}/>}
                                {Object.entries(dataTable).length > 0 ? Object.entries(dataTable).map(([key, value]:any, index) => (
                                <tr key={key} className="hover:bg-gray-100 dark:hover:bg-slate-900">
                                    <td className="px-2 py-1 border text-center">{key}</td>
                                    <td className="px-2 py-1 border text-end">{value.bakuAwal?.sppt}</td>
                                    <td className="px-2 py-1 border text-end">{value.bakuAwal?.jumlah}</td>
                                    <td className="px-2 py-1 border text-end">{value.sppt?.sppt}</td>
                                    <td className="px-2 py-1 border text-end">{value.sppt?.jumlah}</td>
                                    <td className="px-2 py-1 border text-end">{value.penyampaian?.sppt}</td>
                                    <td className="px-2 py-1 border text-end">{value.penyampaian?.jumlah}</td>
                                    <td className="px-2 py-1 border text-end">{value.pembayaran?.sppt}</td>
                                    <td className="px-2 py-1 border text-end">{value.pembayaran?.jumlah}</td>
                                </tr>
                                )):(!loading ?<NoData colSpan={9}/>: null)}
                            </tbody>
                        </table>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
