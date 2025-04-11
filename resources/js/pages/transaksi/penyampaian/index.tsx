import Combobox from '@/components/combobox'
import DataTablePagination from '@/components/data-table/pagination'
import PerPageSelect from '@/components/data-table/per-page-select'
import FormInput from '@/components/form-input'
import { Button } from '@/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { Label } from '@/components/ui/label'
import { alertApp } from '@/components/utils'
import AppLayout from '@/layouts/app-layout'
import { BreadcrumbItem } from '@/types'
import { Head, useForm } from '@inertiajs/react'
import axios from 'axios'
import { Loader2, Search } from 'lucide-react'
import { useEffect, useRef, useState } from 'react'
import DataTable from './components/data-table'

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
        href: 'transaksi.penyampaian.index',
    },
    {
        title: 'Penyampaian',
        href: 'transaksi.penyampaian.index',
    },
];

export default function Index({ gate, jenisBuku }: IndexProps) {
    const title = 'Penyampaian'
    const [loading, setLoading] = useState(false);
    const formRefs = useRef<Record<string, HTMLInputElement | null>>({})
    const [dataTable, setDataTable] = useState<[]>([]);
    const [dataBerdasarkanUser, setDataBerdasarkanUser] = useState<[]>([]);
    const [dataPenyampaianKeterangan, setDataPenyampaianKeterangan] = useState<[]>([]);
    const [linksPagination, setLinksPagination] = useState([]);
    const [infoDataTabel, setInfoDataTabel] = useState({
        page: 1,
        from: 1,
        to: 1,
        total: 0,
        perPage: 25,
    });
    const { data, setData, errors} = useForm({
        jenisBuku : '',
        kelurahan : '',
        kd_blok : '',
        no_urut : '',
    });

    useEffect(() => {
        getDataKelurahanBerdasarkanUser();
        getDataPenyampaianKeterangan();
    }, []);
    
    useEffect(() => {
        if (data.kelurahan) {
            getData();
        }
    }, [infoDataTabel.page, infoDataTabel.perPage]);

    const getData = async () => {
        if (data.kelurahan) {
            setLoading(true);
            try {
                const response = await axios.post(route('transaksi.penyampaian.data'), {
                    page: infoDataTabel.page,
                    perPage: infoDataTabel.perPage,
                    jenisBuku: data.jenisBuku,
                    kelurahan: data.kelurahan,
                    kd_blok: data.kd_blok,
                    no_urut: data.no_urut,
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
        }else{
            alertApp('Pilih kelurahan dulu', 'error');
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
    const getDataPenyampaianKeterangan = async () => {
        try {
            const response = await axios.post(route('master.penyampaian-keterangan.list'));
            setDataPenyampaianKeterangan(response.data);
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
                    <CardHeader>
                        <CardTitle className="text-xl">{title}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <form onSubmit={handleSubmit} className="mb-4 mx-auto">
                            <div className="grid gap-4 lg:grid-cols-3 md:grid-cols-3">
                                <div>
                                    <div className='mb-3'>
                                        <Combobox label="jenisBuku" selectedValue={data.jenisBuku} options={jenisBuku} onSelect={(value) => setData((prevData:any) => ({ ...prevData, jenisBuku: value }))} error={errors.jenisBuku} />
                                    </div>
                                    <div>
                                        <Combobox label="kelurahan" selectedValue={data.kelurahan} options={dataBerdasarkanUser} onSelect={(value) => setData((prevData:any) => ({ ...prevData, kelurahan: value }))} error={errors.kelurahan} />
                                    </div>
                                </div>
                                <div>
                                    <div className='mb-3'>
                                        <FormInput
                                            id="kd_blok"
                                            type="text"
                                            value={data.kd_blok}
                                            onChange={(e) => setData((prevData: any) => ({ ...prevData, kd_blok: e.target.value }))}
                                            inputRef={(el) => {
                                                if (formRefs.current) {
                                                    formRefs.current['kd_blok'] = el;
                                                }
                                            }}
                                            placeholder="Masukkan kd blok"
                                            error={errors.kd_blok}
                                        />
                                    </div>
                                    <FormInput
                                        id="no_urut"
                                        type="text"
                                        value={data.no_urut}
                                        onChange={(e) => setData((prevData: any) => ({ ...prevData, no_urut: e.target.value }))}
                                        inputRef={(el) => {
                                            if (formRefs.current) {
                                                formRefs.current['no_urut'] = el;
                                            }
                                        }}
                                        placeholder="Masukkan no urut"
                                        error={errors.no_urut}
                                    />
                                </div>
                                <div>
                                    <div className='grid gap-2 mb-3'>
                                        <Label htmlFor='halaman'>Jumlah Perhalaman</Label>
                                        <PerPageSelect className='w-full'  onChange={(value) =>setInfoDataTabel((prev:any) => ({...prev,page: 1,perPage: value}))}/>
                                    </div>
                                    <div className='grid gap-2'>
                                        <Label>&nbsp;</Label>
                                        <Button type="submit" className='w-full' disabled={loading}>
                                            {loading ? <Loader2 className="animate-spin" /> : <Search/>} Cari
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <DataTable gate={gate} dataTable={dataTable} loading={loading} dataPenyampaianKeterangan={dataPenyampaianKeterangan} />
                        <DataTablePagination infoDataTabel={infoDataTabel} setInfoDataTabel={setInfoDataTabel} linksPagination={linksPagination} />
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    )
}
