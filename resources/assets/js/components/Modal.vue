<template>
    <div tabindex="-1" role="dialog"
         v-bind:class="{
        'modal':true,
        'fade':effect === 'fade',
        'zoom':effect === 'zoom'
        }"
        id="{{target}}" aria-labelledby="{{target}}"
    >
        <div role="document" v-bind:class="{'modal-dialog':true,'modal-lg':large,'modal-sm':small}">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><strong>{{ title }}</strong></h4>
                </div>
                <div class="modal-body">
                    <slot></slot>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" @click="close">{{ cancelText }}</button>
                    <button type="button" class="btn btn-primary" @click="callback">{{ okText }}</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            okText: {
                type: String,
                default: 'Save changes'
            },
            cancelText: {
                type: String,
                default: 'Close'
            },
            title: {
                type: String,
                default: ''
            },
            target: {
                required: true,
                type: String,
            },
            callback: {
                type: Function,
                default () {}
            },
            effect: {
                type: String,
                default: null
            },
            large: {
                type: Boolean,
                default: false
            },
            small: {
                type: Boolean,
                default: false
            }
        },
        methods: {
            close () {
                this.show = false
            }
        }
    }
</script>
