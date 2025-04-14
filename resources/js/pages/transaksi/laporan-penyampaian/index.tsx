import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Skeleton } from '@/components/ui/skeleton'
import { alertApp } from '@/components/utils'
import AppLayout from '@/layouts/app-layout'
import { BreadcrumbItem } from '@/types'
import { Head, Link } from '@inertiajs/react'
import axios from 'axios'
import { useEffect, useState } from 'react'
import FormCetak from './component/form-cetak'

type IndexProps = {
    gate: {
        create : boolean,
        update : boolean,
    };
    jenisBuku : {
        value : string,
        label : string,
    }[]
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
];

export default function Index({ gate }: IndexProps) {
    const title = "Laporan Penyampaian";
    const [loading, setLoading] = useState(false);
    const [dataJenisLapor, setDataJenisLapor] = useState<[]>([]);

    useEffect(() => {
        getDataKelurahanBerdasarkanUser();
    }, []);
    
    const getDataKelurahanBerdasarkanUser = async () => {
        setLoading(true)
        try {
            const response = await axios.post(route('master.jenis-lapor.list'));
            setDataJenisLapor(response.data);
        } catch (error:any) {
            alertApp(error.message, 'error');
        }finally{
            setLoading(false)
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
                        <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            {loading && (
                                <>
                                    <div><Skeleton className="h-[120px] rounded" /></div>
                                    <div><Skeleton className="h-[120px] rounded" /></div>
                                    <div><Skeleton className="h-[120px] rounded" /></div>
                                </>
                            )}
                            {dataJenisLapor.map((value: any, index: number) => (
                                <Link
                                    key={index}
                                    href={value.status ? route('transaksi.laporan-penyampaian.form', value):''}
                                    className={`grid gap-2 border p-3 rounded-lg ${
                                        !value.status ? ' cursor-not-allowed opacity-50' : ''
                                    }`}
                                    onClick={(e) => !value.status && e.preventDefault()}
                                >
                                    <span className="text-xl font-semibold uppercase">{value.nama}</span>
                                    <span className="text-sm">{value.keterangan}</span>
                                    <span className={`text-xs font-semibold ${!value.status ? 'text-red-300':'text-green-300'}`}>
                                        {value.tanggal_awal} - {value.tanggal_akhir}
                                    </span>
                                </Link>
                            ))}
                        </div>
                        <FormCetak dataJenisLapor={dataJenisLapor}/>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    )
}
