// Import any external JavaScript libraries from NPM here.
import html2pdf from 'html2pdf.js'

export default function testComponent({
    state, isLoading
}) {
    return {
        state,

        element: function (){
            return this.$refs.documentArea;
        },

        pdfWorker: null,

        isLoading,

        options: function() {
            return  {
                jsPDF: {
                    orientation: 'portrait',
                    unit: 'px',
                    format: [512, Math.round(512 * (this.element().offsetHeight / this.element().offsetWidth))]
                },
                html2canvas: {
                    scale: 2,
                    scrollX: 0,
                    scrollY: 0,
                    windowWidth: this.element().offsetWidth*10,
                    windowHeight: this.element().offsetHeight*10
                },
                image: {
                    type: 'jpeg',
                    quality: 1
                },
            }
        },

        pdfUriString: null,

        userShare: function(){
            // console.log(this.pdfUriString, 'String PDF starters')
            if(navigator.canShare){
                let file;
                fetch(this.pdfUriString)
                    .then(res => res.blob())
                    .then(blob => {
                        file = new File([blob], 'Dannalis_payment_reciept_'+Date.now()+'.pdf',{ type: "application/pdf" })
                        navigator.share({
                            title: 'Dannalis Global Resources',
                            text: `Reciept ${state}`,
                            files: [file]
                        })
                    })
            }
        },

        shareFile: async function(){
            this.pdfUriString = await this.pdfWorker.toPdf().output('datauristring')  
            // open sharing modal
            this.$dispatch('open-modal', { id: 'share-modal' })
        },
        
        // You can define any other Alpine.js properties here.
        downloadFile: async function(){
            await this.pdfWorker.save('Dannalis_payment_reciept_'+Date.now()+'.pdf');
        },
 
        init: function () {
            // Initialise the Alpine component here, if you need to.
            this.pdfWorker = html2pdf().set(this.options()).from(this.element())
        },
    }
}