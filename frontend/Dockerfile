FROM node:lts

ARG AWS_ACCESS_KEY_ID
ARG AWS_SECRET_ACCESS_KEY

#Bref
RUN npm install -g serverless@3 && \
    npm install -g serverless-s3-sync
RUN serverless config credentials --provider aws --key $AWS_ACCESS_KEY_ID --secret $AWS_SECRET_ACCESS_KEY 