import Combobox from "@/components/combobox";
import FormInput from "@/components/form-input";
import { Label } from "@/components/ui/label";
import LayerGoogleMap from "./layer-google-map";
import InputError from "@/components/input-error";
type props = {
     data: any
     setData: React.Dispatch<React.SetStateAction<any>>
     formRefs: React.RefObject<Record<string, HTMLInputElement | null>>
     errors: any
     tanahOptions: { value: string; label: string }[]
 }
export default function ObjekPajakDetail({data, setData, formRefs, errors, tanahOptions} : props) {
     return (
     <div>
          <span className='text-2xl font-semibold'>SPESIFIKASI OBJEK PAJAK</span>
          <hr className='my-2' />
     </div>
    )
}
