window.dragDrop = function() {
    return{
        uploadProgress:0,
        dragover(){
            class_to_add = 'dz_on_draged'
            let dzWrapper = document.getElementById('dz-wrapper')
            dzWrapper.classList.add(class_to_add)
            //alert('123')
        },
        dragleave(){
            class_to_remove = 'dz_on_draged'
            let dzWrapper = document.getElementById('dz-wrapper')
            dzWrapper.classList.remove(class_to_remove)
            //alert('456')
        },
        uploadSelected(e){
            if(event.target.files.length>0){
                const files=event.target.files
                this.uploadFiles(files)
            }
        },
        drop(e){
            if(event.dataTransfer.files.length>0){
                const files=e.dataTransfer.files
                this.uploadFiles(files)
            }
        },
        uploadFiles(files){
            @this.uploadMultiple('files',files,
            (success)=>{

            },
            (error)=>{

            },
            (event)=>{
                this.uploadProgress=event.detail.progress
                if (event.detail.progress==100){
                    this.uploadProgress=0
                    document.getElementById('submitButton').click();
                }
            }
            )
        }
    }
}
