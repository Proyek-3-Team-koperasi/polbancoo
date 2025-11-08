<script setup lang="ts" >
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'

const props = defineProps({
    title: { type: String, required: true },
    description: { type: String, default: "" },
    
})

</script>
<template>
      <Card>
                    <CardHeader>
                        <CardTitle>
                            <div class="tw-flex tw-justify-between tw-items-center" >
                                <span>{{ props.title }}</span>
                                <slot name="icon" ></slot>
                            </div>
                        </CardTitle>
                        <CardDescription>{{ props.description }}</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <slot name="content" >

                        </slot>
                    </CardContent>    
                </Card>
</template>