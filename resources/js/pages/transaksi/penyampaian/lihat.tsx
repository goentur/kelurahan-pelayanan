import DataTablePagination from '@/components/data-table/pagination';
import PerPageSelect from '@/components/data-table/per-page-select';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { alertApp } from '@/components/utils';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import axios from 'axios';
import { useEffect, useState } from 'react';
import DataTable from './components/lihat-data/data-table';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Beranda',
        href: 'beranda',
    },
    {
        title: 'Transaksi',
        href: 'transaksi.penyampaian.lihat.index',
    },
    {
        title: 'Penyampaian',
        href: 'transaksi.penyampaian.lihat.index',
    },
    {
        title: 'Lihat',
        href: 'transaksi.penyampaian.lihat.index',
    },
];

export default function Lihat() {
    const title = 'Lihat data Penyampaian';
    const [loading, setLoading] = useState(false);
    const [dataTable, setDataTable] = useState<[]>([]);
    const [linksPagination, setLinksPagination] = useState([]);
    const [infoDataTabel, setInfoDataTabel] = useState({
        page: 1,
        from: 0,
        to: 0,
        total: 0,
        perPage: 25,
    });
        
    useEffect(() => {
        getData();
    }, [infoDataTabel.page, infoDataTabel.perPage]);
    const getData = async () => {
        setLoading(true);
        try {
            const response = await axios.post(route('transaksi.penyampaian.lihat.data'), {
                page: infoDataTabel.page,
                perPage: infoDataTabel.perPage,
            });
            setDataTable(response.data.data);
            setLinksPagination(response.data.links);
            setInfoDataTabel((prev) => ({
                ...prev,
                page: response.data.current_page,
                from: response.data.from,
                to: response.data.to,
                total: response.data.total,
                perPage: response.data.per_page,
            }));
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
                    <CardHeader className='text-center'>
                        <CardTitle className="text-xl uppercase">{title}</CardTitle>
                        <hr className='my-1' />
                    </CardHeader>
                    <CardContent>
                        <div className="w-fit mb-4">
                            <PerPageSelect onChange={(value) => setInfoDataTabel((prev:any) => ({...prev,page: 1,perPage: value}))}/>
                        </div>
                        <DataTable dataTable={dataTable} loading={loading} />
                        <DataTablePagination infoDataTabel={infoDataTabel} setInfoDataTabel={setInfoDataTabel} linksPagination={linksPagination}/>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
