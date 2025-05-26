import Feature from 'ol/Feature'
import Map from 'ol/Map'
import Overlay from 'ol/Overlay'
import View from 'ol/View'
import Point from 'ol/geom/Point'
import { Tile as TileLayer, Vector as VectorLayer } from 'ol/layer'
import 'ol/ol.css'
import { fromLonLat, toLonLat } from 'ol/proj'
import { XYZ } from 'ol/source'
import VectorSource from 'ol/source/Vector'
import { Style } from 'ol/style'
import Icon from 'ol/style/Icon'
import { useEffect, useRef } from 'react'
import poinIcon from '../../../../../../images/poin.png'
type props = {
    data: any
    setData: React.Dispatch<React.SetStateAction<any>>
}
export default function LayerGoogleMap({data, setData} : props) {
    const mapRef = useRef<HTMLDivElement>(null)
    const mapInstance = useRef<Map | null>(null)
    const vectorLayerRef = useRef<VectorLayer | null>(null)
    const overlaysRef = useRef<Overlay[]>([])
    useEffect(() => {
        if (!mapRef.current) return
        if (!mapInstance.current) {
            const googleMapsLayer = new TileLayer({
                source: new XYZ({
                    url: 'https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',
                }),
            })
            const vectorSource = new VectorSource()
            const vectorLayer = new VectorLayer({
                source: vectorSource,
                style: new Style({
                    image: new Icon({
                        anchor: [0.5, 1],
                        src: poinIcon,
                        scale: 0.1,
                    }),
                }),
            })
            const map = new Map({
                target: mapRef.current,
                layers: [googleMapsLayer, vectorLayer],
                view: new View({
                    center: fromLonLat(data.koordinat??[109.66088533825115,-6.8969507196592446]),
                    zoom: 14,
                }),
            })
            mapInstance.current = map
            vectorLayerRef.current = vectorLayer
            map.on('click', (event) => {
                const coordinate = event.coordinate
                const lonLat = toLonLat(coordinate)
                vectorSource?.clear()
                const feature = new Feature({
                    geometry: new Point(fromLonLat(lonLat)),
                })
                vectorSource?.addFeature(feature)
                overlaysRef.current.forEach((overlay) => {
                    map.removeOverlay(overlay)
                })
                overlaysRef.current = []
                const overlay = new Overlay({
                    position: fromLonLat(lonLat),
                })
                map.addOverlay(overlay)
                overlaysRef.current.push(overlay)
                setData((prevData: any) => ({ ...prevData, koordinat: lonLat }))
            })
        }
        const map = mapInstance.current
        if (!map) return
        if (data.koordinat) {
            const vectorLayer = vectorLayerRef.current
            if (!vectorLayer) return
            const vectorSource = vectorLayer.getSource()
            const feature = new Feature({
                geometry: new Point(fromLonLat(data.koordinat)),
            })
            vectorSource?.addFeature(feature)
        }
    }, [])

    return <div ref={mapRef} style={{ width: '100%', height: '35vh' }} />
}