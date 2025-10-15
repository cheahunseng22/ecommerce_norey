# Use an official Node.js runtime as the base image
FROM node:16

# Set the working directory in the container
WORKDIR /app

# Copy package.json and package-lock.json (if they exist)
COPY package*.json ./

# Install dependencies
RUN npm install

# Copy the rest of the files
COPY . .

# Expose port 3000 (default for Vue CLI)
EXPOSE 3000

# Run the app in development mode
CMD ["npm", "run", "dev"]
