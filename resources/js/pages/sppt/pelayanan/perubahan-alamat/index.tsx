import Delete from '@/components/data-table/delete'
import DataTablePagination from '@/components/data-table/pagination'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { alertApp } from '@/components/utils'
import AppLayout from '@/layouts/app-layout'
import { BreadcrumbItem, IndexGate } from '@/types'
import { Head } from '@inertiajs/react'
import axios from 'axios'
import { useEffect, useRef, useState } from 'react'
import DataTable from './components/data-table'
import DialogKonfirmasi from './components/dialog-konfirmasi'
import Filters from './components/filters'
import FormDialog from './components/form-dialog'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Beranda',
        href: 'beranda',
    },
    {
        title: 'SPPT',
        href: 'sppt.data.index',
    },
    {
        title: 'Pelayanan',
        href: 'sppt.pelayanan.perubahan-alamat.index',
    },
    {
        title: 'Perubahan Alamat',
        href: 'sppt.pelayanan.perubahan-alamat.index',
    },
];

export default function Index({ gate }: IndexGate) {
    const title = 'Perubahan Alamat'
    const [form, setForm] = useState(false)
    const [dialogHapus, setDialogHapus] = useState(false)
    const [dialogTerima, setDialogTerima] = useState(false)
    const [dialogTolak, setDialogTolak] = useState(false)
    const [loading, setLoading] = useState(false);
    const [loadingSaveOrUpdate, setLoadingSaveOrUpdate] = useState(false);
    const [loadingHapus, setLoadingHapus] = useState(false);
    const [loadingTerima, setLoadingTerima] = useState(false);
    const [loadingTolak, setLoadingTolak] = useState(false);
    const formRefs = useRef<Record<string, HTMLInputElement | null>>({})
    const [dataTable, setDataTable] = useState<[]>([]);
    const [dataBerdasarkanUser, setDataBerdasarkanUser] = useState<[]>([]);
    const [linksPagination, setLinksPagination] = useState([]);
    const [infoDataTabel, setInfoDataTabel] = useState({
        page: 1,
        from: 0,
        to: 0,
        total: 0,
        perPage: 25,
        status : "SEMUA",
    });
    const [dataForm, setDataForm] = useState({});
    const [data, setData] = useState({
        berdasarkan : "",
        search : "",
    });
    const [dataHapus, setDataHapus] = useState("");
    
    useEffect(() => {
        getData();
        getDataKelurahanBerdasarkanUser();
    }, []);
    
    useEffect(() => {
        getData();
    }, [infoDataTabel.page, infoDataTabel.perPage, infoDataTabel.status]);

    const getData = async () => {
        setLoading(true);
        try {
            const response = await axios.post(route('sppt.pelayanan.perubahan-alamat.data'), {
                page: infoDataTabel.page,
                perPage: infoDataTabel.perPage,
                status: infoDataTabel.status,
                berdasarkan: data.berdasarkan,
                search: data.search,
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
            alertApp(error.response.data.message, 'error');
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
    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoadingSaveOrUpdate(true)
        try {
            const response = await axios.post(route('sppt.pelayanan.perubahan-alamat.simpan-atau-update'),dataForm);
            if (response.data.status) {
                infoDataTabel.page = 1
                alertApp(response.data.message)
                setForm(false)
                setDataForm({})
                getData()
            }else{
                alertApp("Terjadi kesalahan pada saat proses data", 'error');
            }
        } catch (error:any) {
            alertApp(error.response.data.message, 'error');
        }finally{
            setLoadingSaveOrUpdate(false)
        }
    };
    const handleHapus = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoadingHapus(true)
        try {
            const response = await axios.post(route('sppt.pelayanan.perubahan-alamat.hapus',dataHapus));
            if (response.data.status) {
                infoDataTabel.page = 1
                alertApp(response.data.message)
                setDialogHapus(false)
                getData()
            }else{
                alertApp("Terjadi kesalahan pada saat proses data", 'error');
            }
        } catch (error:any) {
            alertApp(error.response.data.message, 'error');
        }finally{
            setLoadingHapus(false)
        }
    };
    const handleTerima = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoadingTerima(true)
        try {
            const response = await axios.post(route('sppt.pelayanan.perubahan-alamat.terima',dataHapus));
            if (response.data.status) {
                infoDataTabel.page = 1
                alertApp(response.data.message)
                setDialogTerima(false)
                getData()
            }else{
                alertApp("Terjadi kesalahan pada saat proses data", 'error');
            }
        } catch (error:any) {
            alertApp(error.response.data.message, 'error');
        }finally{
            setLoadingTerima(false)
        }
    };
    const handleTolak = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoadingTolak(true)
        try {
            const response = await axios.post(route('sppt.pelayanan.perubahan-alamat.hapus',dataHapus));
            if (response.data.status) {
                infoDataTabel.page = 1
                alertApp(response.data.message)
                setDialogTolak(false)
                getData()
            }else{
                alertApp("Terjadi kesalahan pada saat proses data", 'error');
            }
        } catch (error:any) {
            alertApp(error.response.data.message, 'error');
        }finally{
            setLoadingTolak(false)
        }
    };
    const handleCari = (e: React.FormEvent) => {
        e.preventDefault();
        infoDataTabel.page = 1
        getData()
    };
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={title} />
            <Card className='border-0'>
                <CardHeader>
                    <CardTitle className="text-xl">{title}</CardTitle>
                </CardHeader>
                <CardContent>
                    <Filters gate={gate} tambah={() => {setForm(true), setDataForm((prevData: any) => ({ ...prevData, tipe: "tambah" }))}} formRefs={formRefs} handleCari={handleCari} data={data} setData={setData} setInfoDataTabel={setInfoDataTabel} loading={loading}/>
                    <DataTable gate={gate} dataTable={dataTable} loading={loading} setData={setDataHapus} setDialogHapus={setDialogHapus} setDialogTerima={setDialogTerima} setDialogTolak={setDialogTolak} />
                    <DataTablePagination infoDataTabel={infoDataTabel} setInfoDataTabel={setInfoDataTabel} linksPagination={linksPagination} />
                </CardContent>
            </Card>
            <FormDialog
                open={form}
                setOpen={setForm}
                title={title}
                loadingSaveOrUpdate={loadingSaveOrUpdate}
                dataForm={dataForm}
                setDataForm={setDataForm}
                formRefs={formRefs}
                handleForm={handleSubmit}
                dataBerdasarkanUser={dataBerdasarkanUser}
            />
            <DialogKonfirmasi
                judul="Apakah anda yakin ingin menghapus data?"
                deskripsi="Setelah data Anda dihapus, data tidak bisa di kembalikan lagi."
                open={dialogHapus}
                setOpen={setDialogHapus}
                processing={loadingHapus}
                handleSubmit={handleHapus}
            />
            <DialogKonfirmasi
                judul="Apakah anda yakin ingin menerima data?"
                deskripsi="Data yang sudah diterima akan diubah"
                open={dialogTerima}
                setOpen={setDialogTerima}
                processing={loadingTerima}
                handleSubmit={handleTerima}
            />
            <DialogKonfirmasi
                judul="Apakah anda yakin ingin menolak data?"
                deskripsi="Data yang sudah ditolak akan dikembalikan lagi ke kelurahan"
                open={dialogTolak}
                setOpen={setDialogTolak}
                processing={loadingTolak}
                handleSubmit={handleTolak}
            />
        </AppLayout>
    )
}
