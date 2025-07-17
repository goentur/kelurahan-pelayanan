import Combobox from '@/components/combobox';
import DataTablePagination from '@/components/data-table/pagination';
import FormInput from '@/components/form-input';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";
import { alertApp, getYearOptions } from '@/components/utils';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import axios from 'axios';
import { Loader2, Search } from 'lucide-react';
import { useEffect, useRef, useState } from 'react';
import DataTable from './components/pengembalian-data/data-table';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Beranda',
        href: 'beranda',
    },
    {
        title: 'Transaksi',
        href: 'transaksi.penyampaian.pengembalian.index',
    },
    {
        title: 'Penyampaian',
        href: 'transaksi.penyampaian.pengembalian.index',
    },
    {
        title: 'Pengembalian',
        href: 'transaksi.penyampaian.pengembalian.index',
    },
];

export default function Pengembalian() {
    const title = 'Pengembalian data Penyampaian';
    const [loading, setLoading] = useState(false);
    const [dataTable, setDataTable] = useState<[]>([]);
    const [linksPagination, setLinksPagination] = useState([]);
    const [dataBerdasarkanUser, setDataBerdasarkanUser] = useState<[]>([]);
    const formRefs = useRef<Record<string, HTMLInputElement | null>>({})
    const [infoDataTabel, setInfoDataTabel] = useState({
        page: 1,
        from: 0,
        to: 0,
        total: 0,
        perPage: 25,
    });
    const { data, setData, errors} = useForm({
        tahun : new Date().getFullYear(),
        kelurahan : '',
        kd_blok : '',
        no_urut : '',
        nama : '',
    });
        
    useEffect(() => {
        getData();
    }, [infoDataTabel.page, infoDataTabel.perPage]);
    useEffect(() => {
        getDataKelurahanBerdasarkanUser();
    }, []);
    const getData = async () => {
        setLoading(true);
        try {
            const response = await axios.post(route('transaksi.penyampaian.pengembalian.data'), {
                page: infoDataTabel.page,
                perPage: infoDataTabel.perPage,
                tahun: data.tahun,
                kelurahan: data.kelurahan,
                kd_blok: data.kd_blok,
                no_urut: data.no_urut,
                nama: data.nama,
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
    const getDataKelurahanBerdasarkanUser = async () => {
        try {
            const response = await axios.post(route('master.satuan-kerja.data-berdasarkan-user'));
            setDataBerdasarkanUser(response.data);
        } catch (error:any) {
            alertApp(error.message, 'error');
        }
    };
    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        infoDataTabel.page = 1
        getData()
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
                        <form onSubmit={handleSubmit} className="mb-4 mx-auto">
                            <div className="grid gap-4 lg:grid-cols-4 md:grid-cols-4">
                                <div>
                                    <div className="grid gap-2">
                                        <Label>
                                            Tahun Pajak
                                        </Label>
                                        <Select defaultValue={data.tahun.toString()} onValueChange={(value) => {setInfoDataTabel((prev: any) => ({...prev, page: 1})),setData((prev: any) => ({...prev, tahun: value}))}}>
                                            <SelectTrigger className="cursor-pointer">
                                                <SelectValue placeholder="Tahun Pajak" />
                                            </SelectTrigger>
                                            <SelectContent align="start">
                                                {getYearOptions('backward',2).map((num) => (
                                                    <SelectItem key={num.value} value={num.value.toString()}>
                                                        {num.label}
                                                    </SelectItem>
                                                ))}
                                            </SelectContent>
                                        </Select>
                                    </div>
                                </div>
                                <div>
                                    <Combobox label="kelurahan" selectedValue={data.kelurahan} options={dataBerdasarkanUser} onSelect={(value) => setData((prevData:any) => ({ ...prevData, kelurahan: value }))} error={errors.kelurahan} />
                                </div>
                                <div>
                                    <div className='grid gap-4 lg:grid-cols-2 md:grid-cols-2 mb-3'>
                                        <FormInput
                                            id="kd_blok"
                                            type="text"
                                            value={data.kd_blok}
                                            onChange={(e) => {
                                                const value = e.target.value;
                                                setData((prevData: any) => ({ ...prevData, kd_blok: value }));
                                                if (value.length === 3 && formRefs.current?.no_urut) {
                                                    formRefs.current.no_urut.select();
                                                }
                                            }}
                                            inputRef={(el) => {
                                                if (formRefs.current) {
                                                    formRefs.current['kd_blok'] = el;
                                                }
                                            }}
                                            placeholder="Masukkan kd blok"
                                            error={errors.kd_blok}
                                            maxLength={3}
                                        />
                                        <FormInput
                                            id="no_urut"
                                            type="text"
                                            value={data.no_urut}
                                            onChange={(e) => {
                                                const value = e.target.value;
                                                setData((prevData: any) => ({ ...prevData, no_urut: value }));
                                                if (value.length === 4 && formRefs.current?.nama) {
                                                    formRefs.current.nama.select();
                                                }
                                            }}
                                            inputRef={(el) => {
                                                if (formRefs.current) {
                                                    formRefs.current['no_urut'] = el;
                                                }
                                            }}
                                            placeholder="Masukkan no urut"
                                            error={errors.no_urut}
                                            maxLength={4}
                                        />
                                    </div>
                                </div>
                                <div>
                                    <FormInput
                                        id="nama_wajib_pajak"
                                        type="text"
                                        value={data.nama}
                                        onChange={(e) => setData((prevData: any) => ({ ...prevData, nama: e.target.value }))}
                                        inputRef={(el) => {
                                            if (formRefs.current) {
                                                formRefs.current['nama'] = el;
                                            }
                                        }}
                                        placeholder="Masukkan nama wajib pajak"
                                        error={errors.nama}
                                    />
                                    <div className='grid gap-2'>
                                        <Label>&nbsp;</Label>
                                    </div>
                                </div>
                            </div>
                            <Button type="submit" disabled={loading}>
                                {loading ? <Loader2 className="animate-spin" /> : <Search/>} Cari
                            </Button>
                        </form>
                        <DataTable setInfoDataTabel={setInfoDataTabel} dataTable={dataTable} data={data} loading={loading} />
                        <DataTablePagination infoDataTabel={infoDataTabel} setInfoDataTabel={setInfoDataTabel} linksPagination={linksPagination}/>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
