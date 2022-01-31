import { Controller, del } from '@hotwired/stimulus'

/*
 * This is an example Stimulus controller!
 *
 * Any element with a data-controller="hello" attribute will cause
 * this controller to be executed. The name "hello" comes from the filename:
 * hello_controller.js -> "hello"
 *
 * Delete this file or adapt it for your use!
 */
export default class extends Controller {
    picturePrototype;
    static targets = [
      "pictcont",
      "delBtn"
    ]

    connect() {
        this.picturePrototype = this.element.querySelector("#advertisement_form_pictures").dataset.prototype

        this.counter = this.pictcontTarget.children.length
        for (const child of this.pictcontTarget.children) {
            this.addDeleteBtn(child)
        }
    }

    addPicture() {
        const div = document.createElement("div")
        this.pictcontTarget.appendChild(div)
        div.outerHTML = this.picturePrototype.replace(/__name__label__/g, this.counter).replace(/__name__/g, this.counter)

        const row = this.element.querySelector(`#advertisement_form_pictures_${this.counter}`).closest(".row")
        //console.log(row)
        this.addDeleteBtn(row)

        this.counter++
    }

    addDeleteBtn(row) {
        const delBtn = this.delBtnTarget.cloneNode(true)
        delBtn.addEventListener("click", (e) => this.delPicture(e.target) )
        delBtn.classList.remove("d-none")
        row.appendChild(delBtn)
    }

    delPicture(element) {
        element.closest(".row").remove()
    }
}
