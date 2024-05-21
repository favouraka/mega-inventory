// Import any external JavaScript libraries from NPM here.
import html2PDF from 'jspdf-html2canvas'

export default function testComponent({
    state,
}) {
    return {
        state,
        
        // You can define any other Alpine.js properties here.
        shareFile: async function(){
            const element = this.$refs.documentArea;
            // console.log(element.offsetHeight, element.width)
            this.pdfFile = await html2PDF(element, {
                jsPDF: {
                    orientation: 'portrait',
                    unit: 'px',
                    format: [720, Math.round(1080 * (element.offsetHeight / element.offsetWidth))]
                },
                imageType: 'image/jpeg',
                output: 'dannalis_payment_reciept_'+Date.now()+'.pdf',
                html2canvas: {
                    scale: 0.75,
                    scrollX: 0,
                    scrollY: 0,
                    windowWidth: element.offsetWidth,
                    windowHeight: element.offsetHeight
                }
            });

            if(navigator.canShare()){
                navigator.share({
                    files: [this.pdfFile],
                })
            }
        },
 
        init: function () {
            // Initialise the Alpine component here, if you need to.
        },
        
        // You can define any other Alpine.js functions here.
    }
}