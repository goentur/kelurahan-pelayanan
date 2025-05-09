import LoadingData from '@/components/data-table/loading-data';
import NoData from '@/components/data-table/no-data';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { alertApp } from '@/components/utils';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import axios from 'axios';
import React, { useEffect, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Beranda',
        href: 'beranda',
    },
    {
        title: 'Dashboard',
        href: 'dashboard.penyampaian-sppt.index',
    },
    {
        title: 'Penyampaian SPPT',
        href: 'dashboard.penyampaian-sppt.index',
    },
];

export default function Index({jenisLapor} : any) {
    const title = "Penyampaian SPPT"
    const [loading, setLoading] = useState(false);
    const [dataTable, setDataTable] = useState<[]>([]);
    
    useEffect(() => {
        getData();
    }, []);

    const getData = async () => {
        setLoading(true);
        try {
            const response = await axios.post(route('dashboard.penyampaian-sppt.data'));
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
                                    <th className="p-2 border w-1">KODE</th>
                                    <th className="p-2 border">NAMA SATUAN KERJA</th>
                                    <th className="p-2 border w-1">BAKU</th>
                                    {
                                        jenisLapor.map((item: any, index: number) => (
                                            <th key={item.id} className="p-2 border">{item.nama}</th>
                                        ))
                                    }
                                    <th className="p-2 border w-1">TOTAL</th>
                                    <th className="p-2 border w-1">%</th>
                                    <th className="p-2 border w-1">SISA</th>
                                </tr>
                            </thead>
                            <tbody className="font-light">
                                {loading && <LoadingData colSpan={9}/>}
                                {Object.entries(dataTable).length > 0 ? Object.entries(dataTable).map(([key, value]: any, index) => (
                                    <React.Fragment key={`atasan-${key}`}>
                                        <tr className="bg-gray-100 dark:bg-slate-900 font-semibold">
                                            <td className="px-2 py-1 border text-center">{value.kode}</td>
                                            <td className="px-2 py-1 border text-start">{value.nama}</td>
                                            <td className="px-2 py-1 border text-end">{value.baku}</td>
                                            {
                                                jenisLapor.map((item: any) => (
                                                    <td key={`atasan-${key}-${item.id}`} className="px-2 py-1 border text-end">
                                                        {value[`${item.id}`] ?? 0}
                                                    </td>
                                                ))
                                            }
                                            <td className="px-2 py-1 border text-end">{value.total}</td>
                                            <td className="px-2 py-1 border text-end">{value.persen}</td>
                                            <td className="px-2 py-1 border text-end">{value.sisa}</td>
                                        </tr>

                                        {value.bawahan && Array.isArray(value.bawahan) && value.bawahan.map((detail: any, idx: number) => (
                                            <tr key={`bawahan-${key}-${idx}`} className="hover:bg-gray-100 dark:hover:bg-slate-900">
                                                <td className="px-2 py-1 border text-center">{detail.kode}</td>
                                                <td className="px-2 py-1 border text-start">{detail.nama}</td>
                                                <td className="px-2 py-1 border text-end">{detail.baku}</td>
                                                {
                                                    jenisLapor.map((item: any) => (
                                                        <td key={`bawahan-${key}-${idx}-${item.id}`} className="px-2 py-1 border text-end">
                                                            {detail[`${item.id}`] ?? 0}
                                                        </td>
                                                    ))
                                                }
                                                <td className="px-2 py-1 border text-end">{detail.total}</td>
                                                <td className="px-2 py-1 border text-end">{detail.persen}</td>
                                                <td className="px-2 py-1 border text-end">{detail.sisa}</td>
                                            </tr>
                                        ))}
                                    </React.Fragment>
                                )) : (!loading ? <NoData colSpan={jenisLapor.length + 4} /> : null)}

                            </tbody>
                        </table>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
