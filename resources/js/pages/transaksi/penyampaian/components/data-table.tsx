import Combobox from "@/components/combobox";
import LoadingData from "@/components/data-table/loading-data";
import NoData from "@/components/data-table/no-data";
import PerPageSelect from "@/components/data-table/per-page-select";
import FormCalendar from "@/components/form-calendar";
import { alertApp } from "@/components/utils";
import { InfoDataTabel } from "@/types";
import axios from "axios";
import { Loader2, Trash2 } from 'lucide-react';
import { useEffect, useState } from "react";
import Delete from "./delete";
import DialogKeterangan from "./dialog-keterangan";
import { Input } from "@/components/ui/input";
import FormCalendarRange from "@/components/form-calendar-range";

type DataTableProps = {
    gate: {
        create: boolean;
        update: boolean;
        delete: boolean;
    };
    dataTable: any[];
    infoDataTabel: InfoDataTabel;
    setInfoDataTabel: React.Dispatch<React.SetStateAction<any>>
    loading: boolean;
	tanggal: {tanggal_awal : Date, tanggal_akhir : Date};
    dataPenyampaianKeterangan: any[];
};
type TidakTersampaikan = {
  id: string;
  value: string;
  nama_wp: string;
  alamat_objek: string;
  nominal: string;
};

const filterTipe = [
    {
        label : 'SEMUA',
        class : 'bg-cyan-500 hover:bg-cyan-600',
    },
    {
        label : 'BELUM',
        class : 'bg-orange-500 hover:bg-orange-600',
    },
    {
        label : 'TERSAMPAIKAN',
        class : 'bg-green-500 hover:bg-green-600',
    },
    {
        label : 'TIDAK',
        class : 'bg-red-500 hover:bg-red-600',
    },
]

export default function DataTable({ gate, dataTable, infoDataTabel, setInfoDataTabel, loading, tanggal, dataPenyampaianKeterangan }: DataTableProps) {
    const [loadingState, setLoadingState] = useState<{ [key: string]: boolean }>({});
    const [selectedItemTabel, setSelectedItemTabel] = useState<{ [key: string]: boolean }>({});

    // tersampiakan
    const [editedDataYa, setEditedDataYa] = useState<{ [key: string]: {status : boolean, value?: any} }>({});

    // tidak tersampaikan
    const [editedDataTidak, setEditedDataTidak] = useState<{ [key: string]: string }>({});
    // digunakan apabila tidak jadi memilih data yang sudah addAbortListener, jadi nani dikembalikan nilainya ke awal
    const [editedDataTidakSementara, setEditedDataTidakSementara] = useState<{ [key: string]: string }>({});
    const [dataYangTidakTersampaikan, setDataYangTidakTersampaikan] = useState<TidakTersampaikan | null>(null);
    const [dialogKeterangan, setDialogKeterangan] = useState(false)
    
    const [keterangan, setKeterangan] = useState<{ [key: string]: string }>({});

    const [hapus, setHapus] = useState(false)
    const [dataYangDihapus, setDataYangDihapus] = useState<string>()
    const [loadingDeletePerItem, setLoadingDeletePerItem] = useState<{ [key: string]: boolean }>({});
    const [loadingDelete, setLoadingDelete] = useState<boolean>(false)

    
    const [tipe, setTipe] = useState<{ [key: string]: { type: boolean; message: string } | null }>({});
    const [messages, setMessages] = useState<{ [key: string]: { type: boolean; message: string } | null }>({});

    const handleSelectItem = (index: string, isYes: boolean) => {
        setSelectedItemTabel((prev) => ({ ...prev, [index]: isYes }));
    };

    const handleSelectTersampaikan = async (id: string, value: any,nama_wp : string, alamat_objek : string,nominal: string) => {
        setLoadingState((prev) => ({ ...prev, [id]: true }));
        try {
            const response = await axios.post(route('transaksi.penyampaian.tersampaikan'), {
                id: id,
                value: value,
                nama_wp: nama_wp,
                alamat_objek: alamat_objek,
                nominal: nominal,
            });
            
            setEditedDataYa((prev) => ({
                ...prev,
                [id]: {
                    status : true,
                    value : value
                },
            }));

            setTipe((prev) => ({
                ...prev,
                [id]: { type: response.data?.status, message: response.data?.value},
            }));

            setMessages((prev) => ({
                ...prev,
                [id]: { type: response.data?.status, message: response.data?.message},
            }));
            
            setKeterangan((prev) => ({
                ...prev,
                [id]: "Tersampaikan ke Wajib Pajak",
            }));
            
            setEditedDataTidak((prev) => {
                const data = { ...prev };
                delete data[id];
                return data;
            });

        } catch (error:any) {
            setMessages((prev) => ({
                ...prev,
                [id]: { type: false, message: "GAGAL"},
            }));
        } finally {
            setLoadingState((prev) => ({ ...prev, [id]: false }));
        }
    };
    
    const handleSelectItemTidak = (id: string, value:string, nama_wp : string, alamat_objek : string, nominal: string) => { 
        if (!editedDataTidak[id]) {
            setEditedDataTidakSementara(prev => ({
                ...prev,
                [id]: value,
            }));
        }
        setEditedDataTidak(prev => ({
            ...prev,
            [id]: value,
        }));
        const data = { id, value, nama_wp, alamat_objek, nominal };
        setDataYangTidakTersampaikan(data);
        if (data) {
            setDialogKeterangan(true);
        }
    }
    const handleDialogKeterangan = (isOpen: boolean) => {
        setDialogKeterangan(isOpen);
        if (
        !isOpen &&
        dataYangTidakTersampaikan?.id &&
        (!keterangan[dataYangTidakTersampaikan.id]?.trim() ||
            keterangan[dataYangTidakTersampaikan.id].trim() === '')
        ) {
            setEditedDataTidak((prev) => {
                const data = { ...prev };
                delete data[dataYangTidakTersampaikan.id];
                return data;
            });
        } else if (dataYangTidakTersampaikan?.id) {
            setEditedDataTidak((prev) => ({
                ...prev,
                [dataYangTidakTersampaikan.id]: editedDataTidakSementara[dataYangTidakTersampaikan.id],
            }));
        }

    };

    const handleTidakTersampaikan = (id:string, status: boolean, value: any,message : string, keterangan : string) => {
        setDialogKeterangan(false)
        setTipe((prev) => ({
            ...prev,
            [id]: { type: status, message: value},
        }));

        setMessages((prev) => ({
            ...prev,
            [id]: { type: true, message: message},
        }));
        setEditedDataTidakSementara(prev => ({
            ...prev,
            [id]: editedDataTidak[id],
        }));
        setKeterangan((prev) => ({
            ...prev,
            [id]: keterangan,
        }));

        setEditedDataYa((prev) => {
            const data = { ...prev };
            delete data[id];
            return data;
        });
    };

    const handleDeleteItem = (id: string) => { 
        setDataYangDihapus(id);
        setHapus(true);
    }

    const deleteItem = async () => {
        if (!dataYangDihapus) return;
        const id = dataYangDihapus;
        setLoadingDeletePerItem((prev) => ({ ...prev, [id]: true }));
        setLoadingDelete(true)
        try {
            const response = await axios.post(route('transaksi.penyampaian.delete'), { id });

            if (response.data.status) {
                setLoadingState((prev) => {
                    const dataLoadingState = { ...prev };
                    delete dataLoadingState[id];
                    return dataLoadingState;
                })
                setSelectedItemTabel((prev) => {
                    const dataSelectedItemTabel = { ...prev };
                    delete dataSelectedItemTabel[id];
                    return dataSelectedItemTabel;
                })
                setEditedDataYa((prev) => {
                    const dataEditDataYa = { ...prev };
                    delete dataEditDataYa[id];
                    return dataEditDataYa;
                })
                setEditedDataTidak((prev) => {
                    const dataEditDataTidak = { ...prev };
                    delete dataEditDataTidak[id];
                    return dataEditDataTidak;
                })
                setEditedDataTidakSementara((prev) => {
                    const dataEditedDataTidakSementara = { ...prev };
                    delete dataEditedDataTidakSementara[id];
                    return dataEditedDataTidakSementara;
                })
                setKeterangan((prev) => {
                    const dataKeterangan = { ...prev };
                    delete dataKeterangan[id];
                    return dataKeterangan;
                })
                setLoadingDeletePerItem((prev) => {
                    const dataLoadingDeletePErItem = { ...prev };
                    delete dataLoadingDeletePErItem[id];
                    return dataLoadingDeletePErItem;
                })
                setTipe((prev) => {
                    const dataTipe = { ...prev };
                    delete dataTipe[id];
                    return dataTipe;
                })
                setMessages((prev) => {
                    const dataMessages = { ...prev };
                    delete dataMessages[id];
                    return dataMessages;
                })

                alertApp(response.data.message);
            }
        } catch (error: any) {
            alertApp(error.message, "error");
        } finally {
            setLoadingDelete(false)
            setLoadingDeletePerItem((prev) => ({ ...prev, [id]: false }));
            setHapus(false);
            setDataYangDihapus(undefined);
        }
    };

    useEffect(() => {
        setLoadingState({})
        setSelectedItemTabel({})
        setEditedDataYa({})
        setEditedDataTidak({})
        setEditedDataTidakSementara({})
        setKeterangan({})
        setLoadingDeletePerItem({})
        setTipe({})
        setMessages({})
        dataTable.forEach((value: any) => {
            if(value.penyampaian){
                setTipe((prev) => ({
                    ...prev,
                    [value.id]: { type: value.penyampaian.tipe.status, message: value.penyampaian.tipe.label},
                }));
                if (value.penyampaian.status.status) {
                    setSelectedItemTabel((prev) => ({ ...prev, [value.id]: value.penyampaian.tipe.status }));
                    if(value.penyampaian.tipe.status){
                        setEditedDataYa((prev) => ({
                            ...prev,
                            [value.id]: {
                                status: true,
                                value : new Date(value.penyampaian.tipe.value)
                            },
                        }));
                    }else{
                        setEditedDataTidak((prev) => ({
                            ...prev,
                            [value.id]: value.penyampaian.tipe.value,
                        }));
                        setEditedDataTidakSementara(prev => ({
                            ...prev,
                            [value.id]: value.penyampaian.tipe.value,
                        }));
                    }
                }
                setKeterangan((prev) => ({
                    ...prev,
                    [value.id]: value.penyampaian.tipe.catatan,
                }));
                setMessages((prev) => ({
                    ...prev,
                    [value.id]: { type: value.penyampaian.status.status, message: value.penyampaian.status.value},
                }));
            }
        });
    }, [dataTable]);
    return (
        <div>
            <ul className="mb-2 list-disc list-inside bg-blue-200 p-2 text-black rounded">
                <li>Pilih data yang ingin diperbarui.</li>
                <li>
                    Tentukan status penyampaian dengan memilih <button className="p-0.5 text-[12px] bg-green-500 text-white m-0 rounded hover:bg-green-600">YA</button> atau <button className="p-0.5 text-[12px] bg-red-500 text-white m-0 rounded hover:bg-red-600">TIDAK</button>:
                    <ul className="ml-4 list-disc list-inside">
                        <li>Jika memilih <button className="p-0.5 text-[12px] bg-green-500 text-white m-0 rounded hover:bg-green-600">YA</button>, pilih tanggal penyampaian pada kolom keterangan.</li>
                        <li>Jika memilih <button className="p-0.5 text-[12px] bg-red-500 text-white m-0 rounded hover:bg-red-600">TIDAK</button>, pilih alasan yang sesuai pada kolom keterangan.</li>
                    </ul>
                </li>
            </ul>
            <div className="mb-1 flex items-center justify-between flex-wrap gap-4">
                <div className="flex flex-col gap-2">
                    <PerPageSelect
                        onChange={(value) =>
                            setInfoDataTabel((prev: any) => ({ ...prev, page: 1, perPage: value }))
                        }
                    />
                </div>
                <div className="flex gap-1">
                    {filterTipe.map((item: any, index: number) => (
                    <button
                        type="button"
                        onClick={() =>
                            setInfoDataTabel((prev: any) => ({
                                ...prev,
                                page: 1,
                                tipe: item.label,
                            }))
                        }
                        disabled={loading || infoDataTabel.tipe == item.label}
                        key={index}
                        className={`${item.class} p-1 text-xs text-white rounded cursor-pointer disabled:cursor-not-allowed`}
                    >
                        {item.label}
                    </button>
                    ))}
                </div>
            </div>
            <table className="w-full text-left border-collapse border">
                <thead className="text-center text-sm">
                <tr className="uppercase leading-normal">
                    <th colSpan={3} className="px-2 py-1 border w-1">NOP</th>
                    <th className="px-2 py-1 border w-1/4">Nama wajib pajak</th>
                    <th className="px-2 py-1 border w-1">Pajak</th>
                    <th className="px-2 py-1 border w-1">Tipe</th>
                    <th colSpan={2} className="px-2 py-1 border text-center">TERSAMPAIKAN?</th>
                    <th className="px-2 py-1 border">Form</th>
                    <th className="px-2 py-1 border">Keterangan</th>
                    <th className="px-2 py-1 border w-1">Status</th>
                    <th className="px-2 py-1 border w-1">#</th>
                </tr>
                </thead>
                <tbody className="font-light text-xs">
                {loading && <LoadingData colSpan={12}/>}
                {dataTable.length > 0 ? (dataTable.map((value: any, index: number) => (
                    <tr key={index} className="hover:bg-gray-100 dark:hover:bg-slate-900">
                        <td className="px-1 py-1 border w-1 text-sm">{value.blok}</td>
                        <td className="px-1 py-1 border w-1 text-sm">{value.no}</td>
                        <td className="px-1 py-1 border w-1 text-sm">{value.jenis}</td>
                        <td className="px-2 py-1 border">
                            <span className="text-xs font-semibold">{value.nama_wp}</span>
                            <br />
                            <span className="text-[11px]">{value.alamat_objek}</span>
                        </td>
                        <td className="px-2 py-1 border text-end">{value.pajak}</td>
                        <td className="px-2 py-1 border text-center">
                            {tipe[value.id] && tipe[value.id]?.type!=null && (
                                <span className={`px-1 rounded text-white ${tipe[value.id]?.type ? "bg-green-500" : "bg-red-500"}`}>
                                    {tipe[value.id]?.message}
                                </span>
                            )}
                        </td>
                        <td className="border w-1 text-center">
                            {(gate.create || gate.update) && (!messages[value.id] || messages[value.id]?.message=="SIMPAN") && (
                                <button
                                    type="button"
                                    onClick={() => handleSelectItem(value.id, true)}
                                    className="p-0.5 hidden text-[12px] bg-green-500 text-white m-0 rounded hover:bg-green-600 cursor-pointer disabled:cursor-not-allowed"
                                    // disabled={loadingState[value.id] || loadingDeletePerItem[value.id]}
                                    disabled={true}
                                >
                                    {loadingState[value.id] ? <Loader2 className="animate-spin p-1" size={20} /> : 'YA'}
                                </button>
                            )}
                        </td>
                        <td className="border w-1 text-center">
                            {(gate.create || gate.update) && (!messages[value.id] || messages[value.id]?.message=="SIMPAN") && ( 
                                <button
                                    type="button"
                                    onClick={() => handleSelectItem(value.id, false)}
                                    className="p-0.5 text-[12px] bg-red-500 text-white m-0 rounded hover:bg-red-600 cursor-pointer disabled:cursor-not-allowed"
                                    disabled={loadingState[value.id] || loadingDeletePerItem[value.id]}
                                >
                                    {loadingState[value.id] ? <Loader2 className="animate-spin p-1" size={20} /> : 'TIDAK'}
                                </button>
                            )}
                        </td>
                        <td className="px-1 py-1 border">
                            
                            {selectedItemTabel[value.id] == true ? (
                                <FormCalendarRange
                                    value={editedDataYa[value.id]?.value}
                                    onChange={(v) => handleSelectTersampaikan(value.id, v, value.nama_wp, value.alamat_objek, value.pajak)}
                                    autoOpen={true}
                                    disabled={!gate.create || !gate.update || loadingDeletePerItem[value.id]}
                                    placeholder="Pilih tanggal"
                                    tanggal={tanggal}
                                />
                            ) : null}

                            {selectedItemTabel[value.id] == false ? (
                                <div>
                                    <Combobox
                                        label=""
                                        selectedValue={editedDataTidak[value.id] || ""}
                                        options={dataPenyampaianKeterangan}
                                        onSelect={(v) => handleSelectItemTidak(value.id, v, value.nama_wp, value.alamat_objek, value.pajak)}
                                        autoOpen={true}
                                        disabled={!gate.create || !gate.update || loadingDeletePerItem[value.id]}
                                    />
                                </div>
                            ) : null }
                            {!messages[value.id]?.type && value.penyampaian?.tipe.value}
                        </td>
                        <td className="px-2 py-1 border">
                            {keterangan[value.id]}
                        </td>
                        <td className="px-2 py-1 border text-center">
                            {messages[value.id] && (
                                <span className={`px-1 rounded text-white ${messages[value.id]?.type ? "bg-green-500" : "bg-red-500"}`}>
                                    {messages[value.id]?.message}
                                </span>
                            )}
                        </td>
                        <td className="px-2 py-1 border text-center">
                            {gate.delete && messages[value.id] && messages[value.id]?.type && (
                                <button
                                    type="button"
                                    onClick={() => handleDeleteItem(value.id)}
                                    className="p-0.5 bg-red-500 text-white m-0 rounded hover:bg-red-600 cursor-pointer"
                                    disabled={loadingDeletePerItem[value.id]}
                                >
                                    {loadingDeletePerItem[value.id] ? <Loader2 className="animate-spin p-1" size={20} /> : <Trash2 size={20} />}
                                </button>
                            )}
                        </td>
                    </tr>
                    ))
                ) : (!loading ?<NoData colSpan={12}/>: null)}
                </tbody>
            </table>
            <Delete
                open={hapus}
                setOpen={setHapus}
                processing={loadingDelete}
                handleHapusData={deleteItem}
            />
            <DialogKeterangan
                open={dialogKeterangan}
                setOpen={handleDialogKeterangan}
                dataYangTidakTersampaikan={dataYangTidakTersampaikan}
                handleTidakTersampaikan={handleTidakTersampaikan}
            />
        </div>
    );
}
