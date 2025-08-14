import { Building2, LandPlot, MapPin } from "lucide-react";
type props = {
    data: any
}
export default function Umum({data} : props) {
     return (
          <>
               <div className="grid grid-cols-1 md:grid-cols-12 gap-4 text-sm">
                    <div className="col-span-12 md:col-span-6 border border-blue-500 shadow rounded-lg">
                         <div className="px-4 py-2 flex items-center gap-2 font-semibold border-b border-blue-500">
                              <MapPin className="w-4 h-4" />
                              <span>INFORMASI UMUM</span>
                         </div>
                         <div className="p-4">
                              <table className="w-full">
                                   <tbody>
                                        <tr>
                                             <td className="py-1 text-left align-top font-medium w-[15%]">JENIS</td>
                                             <td className="py-1 text-center align-top w-[10%]">:</td>
                                             <td className="py-1 text-left align-top">{data.objek.jenis}</td>
                                        </tr>
                                        <tr>
                                             <td className="py-1 text-left align-top font-medium">ALAMAT</td>
                                             <td className="py-1 text-center align-top w-[10%]">:</td>
                                             <td className="py-1 text-left align-top">{data.objek.alamat}</td>
                                        </tr>
                                   </tbody>
                              </table>
                         </div>
                    </div>

                    <div className="col-span-12 md:col-span-6 border border-green-500 shadow rounded-lg">
                         <div className="px-4 py-2 flex items-center gap-2 font-semibold border-b border-green-500">
                              <MapPin className="w-4 h-4" />
                              <span>SUBJEK PAJAK</span>
                         </div>
                         <div className="p-4">
                              <table className="w-full">
                                   <tbody>
                                        <tr className="border-b align-top">
                                             <td className="py-1 text-left align-top font-medium w-[15%]">NPWP</td>
                                             <td className="py-1 text-center align-top w-[10%]">:</td>
                                             <td className="py-1 text-left align-top">{data.subjek.npwp}</td>
                                        </tr>
                                        <tr className="border-b align-top">
                                             <td className="py-1 text-left align-top font-medium">NAMA</td>
                                             <td className="py-1 text-center align-top">:</td>
                                             <td className="py-1 text-left align-top">{data.subjek.nama}</td>
                                        </tr>
                                        <tr className="border-b align-top">
                                             <td className="py-1 text-left align-top font-medium">STATUS</td>
                                             <td className="py-1 text-center align-top">:</td>
                                             <td className="py-1 text-left align-top">{data.subjek.status}</td>
                                        </tr>
                                        <tr className="align-top">
                                             <td className="py-1 text-left align-top font-medium">ALAMAT</td>
                                             <td className="py-1 text-center align-top">:</td>
                                             <td className="py-1 text-left align-top">{data.subjek.alamat}</td>
                                        </tr>
                                   </tbody>
                              </table>
                         </div>
                    </div>

                    <div className="col-span-12 md:col-span-6 border border-blue-500 shadow rounded-lg">
                         <div className="px-4 py-2 flex items-center gap-2 font-semibold border-b border-blue-500">
                              <LandPlot className="w-4 h-4" />
                              <span>INFORMASI TANAH</span>
                         </div>
                         <div className="p-4">
                              <table className="w-full">
                                   <tbody>
                                        <tr>
                                             <td className="py-1 text-left align-top font-medium w-[15%]">ZNT</td>
                                             <td className="py-1 text-center align-top w-[10%]">:</td>
                                             <td className="py-1 text-left align-top">{data.objek.tanah.znt}</td>
                                        </tr>
                                        <tr>
                                             <td className="py-1 text-left align-top font-medium">LUAS</td>
                                             <td className="py-1 text-center align-top w-[10%]">:</td>
                                             <td className="py-1 text-left align-top">{data.objek.tanah.luas} m²</td>
                                        </tr>
                                        <tr>
                                             <td className="py-1 text-left align-top font-medium">NJOP</td>
                                             <td className="py-1 text-center align-top w-[10%]">:</td>
                                             <td className="py-1 text-left align-top">{data.objek.tanah.njop}</td>
                                        </tr>
                                   </tbody>
                              </table>
                         </div>
                    </div>

                    
                    <div className="col-span-12 md:col-span-6 border border-blue-500 shadow rounded-lg">
                         <div className="px-4 py-2 flex items-center gap-2 font-semibold border-b border-blue-500">
                         <Building2 className="w-4 h-4" />
                              <span>INFORMASI BANGUNAN</span>
                         </div>
                         <div className="p-4">
                              <table className="w-full">
                                   <tbody>
                                        <tr>
                                             <td className="py-1 text-left align-top font-medium w-[15%]">JUMLAH</td>
                                             <td className="py-1 text-center align-top w-[10%]">:</td>
                                             <td className="py-1 text-left align-top">{data.objek.bangunan.jumlah}</td>
                                        </tr>
                                        <tr>
                                             <td className="py-1 text-left align-top font-medium w-[15%]">LUAS</td>
                                             <td className="py-1 text-center align-top w-[10%]">:</td>
                                             <td className="py-1 text-left align-top">{data.objek.bangunan.luas} m²</td>
                                        </tr>
                                        <tr>
                                             <td className="py-1 text-left align-top font-medium">NJOP</td>
                                             <td className="py-1 text-center align-top w-[10%]">:</td>
                                             <td className="py-1 text-left align-top">{data.objek.bangunan.njop}</td>
                                        </tr>
                                   </tbody>
                              </table>
                         </div>
                    </div>
               </div>

               <div className="flex justify-between items-center text-xl font-semibold px-4 py-3 rounded-md border border-primary mt-3 mb-3">
                    <span>TOTAL NJOP</span>
                    <span className="text-end">{data.objek.njop}</span>
               </div>
          </>
     )
}
