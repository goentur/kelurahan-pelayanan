import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { alertApp } from '@/components/utils'
import AppLayout from '@/layouts/app-layout'
import { BreadcrumbItem, InfoDataTabel } from '@/types'
import { Head, useForm } from '@inertiajs/react'
import axios from 'axios'
import { useEffect, useRef, useState } from 'react'
import DataTable from './components/data-table'
import FormDialog from './components/form-dialog'
import DataTablePagination from '@/components/data-table/pagination'
import DataTableFilters from './components/filters'
import FormDialogDetail from './components/form-dialog-detail'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Beranda',
        href: 'beranda',
    },
    {
        title: 'Pemberkasan',
        href: 'pendataan.spop.index',
    },
    {
        title: 'SPOP',
        href: 'pendataan.spop.index',
    },
];

export default function Index({ gate, jenis, status, pekerjaan, tanah, jenisBangunan, kondisi, konstruksi, atap, dinding, lantai, langit }: any) {
    
    const title = 'SPOP'
    const formRefs = useRef<Record<string, HTMLInputElement | null>>({})
    const [form, setForm] = useState(false)
    const [formDetail, setFormDetail] = useState(false)
    const [isEdit, setIsEdit] = useState(false)
    const [loading, setLoading] = useState(false)
    const [dataTable, setDataTable] = useState<[]>([])
    const [linksPagination, setLinksPagination] = useState([])
    const [hapus, setHapus] = useState(false)
    const [dataDetail, setDataDetail] = useState([])
    const [infoDataTabel, setInfoDataTabel] = useState<InfoDataTabel>({
        page: 1,
        from: 0,
        to: 0,
        total: 0,
        perPage: 25,
        berdasarkan: null,
        search: null,
    })
    const mapToOptions = (data: any[]) => data.map(item => ({ label: item.nama, value: item.id }));
    const jenisOptions = mapToOptions(jenis);
    const statusOptions = mapToOptions(status);
    const pekerjaanOptions = mapToOptions(pekerjaan);
    const tanahOptions = mapToOptions(tanah);
    const jenisBangunanOptions = mapToOptions(jenisBangunan);
    const kondisiOptions = mapToOptions(kondisi);
    const konstruksiOptions = mapToOptions(konstruksi);
    const atapOptions = mapToOptions(atap);
    const dindingOptions = mapToOptions(dinding);
    const lantaiOptions = mapToOptions(lantai);
    const langitOptions = mapToOptions(langit);
      
    const { data, setData, errors, post, patch, reset, processing} = useForm({
        jenis : "",
        kd_propinsi : "33",
        kd_dati2 : "75",
        kd_kecamatan : "040",
        kd_kelurahan : "007",
        kd_blok : "",
        no_urut : "",
        kd_jns_op : "",
        jalan : "",
        blok_kav_no : "",
        rw : "",
        rt : "",
        luas_tanah : "",
        no_sertipikat : "",
        tanah : "",
        keterangan : "",
        koordinat : null,
        status : "",
        pekerjaan : "",
        nik : "",
        npwp : "",
        nama : "",
        jalan_sp : "",
        blok_kav_no_sp : "",
        rw_sp : "",
        rt_sp : "",
        kelurahan : "",
        kecamatan : "",
        kota : "",
        kode_pos : "",
        no_telp : "",
        email : "",
        jenis_bangunan : "",
        luas_bangunan : "",
        jumlah_lantai : "",
        tahun_dibangun : "",
        tahun_renovasi : "",
        daya_listrik : "",
        jumlah_ac : "",
        kondisi : "",
        konstruksi : "",
        atap : "",
        dinding : "",
        lantai : "",
        langit : "",
    });
    useEffect(() => {
        getData();
    }, []);
    
    useEffect(() => {
        getData();
    }, [infoDataTabel.page, infoDataTabel.perPage]);

    const getData = async () => {
        setLoading(true);
        try {
            const response = await axios.post(route('pendataan.spop.data'), {
                page: infoDataTabel.page,
                perPage: infoDataTabel.perPage,
                berdasarkan: infoDataTabel.berdasarkan,
                search: infoDataTabel.search,
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
    const handleCari = (e: React.FormEvent) => {
        e.preventDefault()
        getData()
    }
    const handleForm = (e: React.FormEvent) => {
        e.preventDefault()
        const action = isEdit ? patch : post
        const routeName = isEdit
            ? (route('pendataan.spop.update', data) as string)
            : (route('pendataan.spop.store') as string)
        action(routeName, {
            preserveScroll: true,
            onSuccess: (e) => {
                reset()
                alertApp(e)
                setForm(false)
            },
            onError: (e) => {
                const firstErrorKey = Object.keys(e)[0]
                if (firstErrorKey) {
                    formRefs.current[firstErrorKey]?.focus()
                }
            },
        })
    }
    const cekNop = async () => {
        try {
            const response = await axios.post(route('pendataan.spop.cek-nop'), data);
            if (!response.data.status) {
                alertApp(response.data.message,"error")
            }
        } catch (error:any) {
            alertApp(error.response.data.message, 'error');
        }
    };
    useEffect(() => {
        const allFilled = data.kd_propinsi && data.kd_dati2 && data.kd_kecamatan && data.kd_kelurahan && data.kd_blok && data.no_urut && data.kd_jns_op;
        if (allFilled) {
            cekNop();
        }
    }, [data.kd_propinsi, data.kd_dati2, data.kd_kecamatan, data.kd_kelurahan, data.kd_blok, data.no_urut, data.kd_jns_op]);
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={title} />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <CardTitle className="text-xl">{title}</CardTitle>
                <DataTableFilters
                    gate={gate}
                    tambah={() => {reset(), setForm(true), setIsEdit(false)}}
                    formRefs={formRefs}
                    handleCari={handleCari}
                    infoDataTabel={infoDataTabel}
                    setInfoDataTabel={setInfoDataTabel}
                    loading={loading}
                />
                <DataTable
                    gate={gate}
                    loading={loading}
                    data={dataTable}
                    from={infoDataTabel.from}
                    setForm={setForm}
                    setFormDetail={setFormDetail}
                    setIsEdit={setIsEdit}
                    setData={setData}
                    setDataDetail={setDataDetail}
                    setHapus={setHapus}
                />
                <DataTablePagination
                    infoDataTabel={infoDataTabel}
                    setInfoDataTabel={setInfoDataTabel}
                    linksPagination={linksPagination}
                />
            </div>
            <FormDialog
                open={form}
                setOpen={setForm}
                title={title}
                isEdit={isEdit}
                data={data}
                setData={setData}
                errors={errors}
                formRefs={formRefs}
                processing={processing}
                handleForm={handleForm}
                jenisOptions={jenisOptions}
                tanahOptions={tanahOptions}
                statusOptions={statusOptions}
                pekerjaanOptions={pekerjaanOptions}
                jenisBangunanOptions={jenisBangunanOptions}
                kondisiOptions={kondisiOptions}
                konstruksiOptions={konstruksiOptions}
                atapOptions={atapOptions}
                dindingOptions={dindingOptions}
                lantaiOptions={lantaiOptions}
                langitOptions={langitOptions}
            />
            <FormDialogDetail
                open={formDetail}
                setOpen={setFormDetail}
                title={title}
                dataDetail={dataDetail}
                data={data}
                setData={setData}
                errors={errors}
                formRefs={formRefs}
                processing={processing}
                handleForm={handleForm}
                jenisBangunanOptions={jenisBangunanOptions}
                kondisiOptions={kondisiOptions}
                konstruksiOptions={konstruksiOptions}
                atapOptions={atapOptions}
                dindingOptions={dindingOptions}
                lantaiOptions={lantaiOptions}
                langitOptions={langitOptions}
            />
            {/* <Delete
                open={hapus}
                setOpen={setHapus}
                processing={processing}
                handleHapusData={handleHapus}
            />  */}
        </AppLayout>
    )
}
