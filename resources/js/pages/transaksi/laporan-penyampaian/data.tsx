import DataTablePagination from '@/components/data-table/pagination';
import PerPageSelect from '@/components/data-table/per-page-select';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import { alertApp } from '@/components/utils';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import axios from 'axios';
import { Loader2, Send } from 'lucide-react';
import { useEffect, useState } from 'react';
import DataTable from './component/data-table';

type dataProps = {
    jenisLapor : any
};
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Beranda',
        href: 'beranda',
    },
    {
        title: 'Transaksi',
        href: 'transaksi.laporan-penyampaian.index',
    },
    {
        title: 'Laporan Penyampaian',
        href: 'transaksi.laporan-penyampaian.index',
    },
    {
        title: 'Data Laporan Penyampaian',
        href: 'transaksi.laporan-penyampaian.index',
    },
];

export default function Data({jenisLapor}:dataProps) {
    const title = 'Laporan '+jenisLapor.nama;
    const [isChecked, setIsChecked] = useState(false);
    const [loading, setLoading] = useState(false);
    const [loadingKirimData, setLoadingKirimData] = useState(false);
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
            const response = await axios.post(route('transaksi.laporan-penyampaian.data'), {
                page: infoDataTabel.page,
                perPage: infoDataTabel.perPage,
                jenis: jenisLapor.jenis,
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
    const handleKirimData = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoadingKirimData(true);
        try {
            const response = await axios.post(route('transaksi.laporan-penyampaian.simpan'), {
                id: jenisLapor.id,
                jenis: jenisLapor.jenis,
            });
            if (response.data.status) {
                setDataTable([]);
                setIsChecked(false);
                setInfoDataTabel((prev) => ({
                    ...prev,
                    page: 1,
                    from: 0,
                    to: 0,
                    total: 0,
                    perPage: 25,
                }));
                setLinksPagination([]);
                alertApp(response.data.message);
            } else {
                alertApp(response.data.message, 'error');
            }
        } catch (error:any) {
            alertApp(error.message, 'error');
        } finally {
            setLoadingKirimData(false);
        }
    };
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={title} />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Card>
                    <CardHeader className='text-center'>
                        <CardTitle className="text-xl uppercase">{title}</CardTitle>
                        <CardTitle className="text-sm text-green-500">{jenisLapor.tanggal_awal} S.D. {jenisLapor.tanggal_akhir}</CardTitle>
                        <hr className='mt-3 mb-3' />
                    </CardHeader>
                    <CardContent>
                        <div className="w-fit mb-4">
                            <PerPageSelect onChange={(value) => setInfoDataTabel((prev:any) => ({...prev,page: 1,perPage: value}))}/>
                        </div>
                        <DataTable dataTable={dataTable} loading={loading} />
                        <DataTablePagination infoDataTabel={infoDataTabel} setInfoDataTabel={setInfoDataTabel} linksPagination={linksPagination}/>
                    </CardContent>
                    <CardFooter>
                        <form onSubmit={handleKirimData} className='grid gap-2'>
                            <div className="flex items-center space-x-2">
                                <Checkbox id="persetujuan" checked={isChecked} onCheckedChange={(checked: boolean) => setIsChecked(checked)} required disabled={dataTable.length==0} />
                                <label
                                    htmlFor="persetujuan"
                                    className="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
                                >
                                    Silakan centang kotak di samping untuk mengonfirmasi bahwa data yang dikirim sudah benar dan sesuai.
                                </label>
                            </div>
                            <div>
                                <Button type="submit" disabled={loadingKirimData || dataTable.length==0}>
                                    {loadingKirimData ? <Loader2 className="animate-spin" /> : <Send/>} Kirim Data
                                </Button>
                            </div>
                        </form>
                    </CardFooter>
                </Card>
            </div>
        </AppLayout>
    );
}
